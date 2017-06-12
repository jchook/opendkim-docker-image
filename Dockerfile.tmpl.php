<?php
$var = getopt('', ['dockerfile:']);
$isAlpineImage = (end(explode('/', $var['dockerfile'])) === 'alpine');

$OpenDKIMVer = '2.10.3';
$OpenDKIMSha512Sum = '97923e533d072c07ae4d16a46cbed95ee799aa50f19468d8bc6d1dc534025a8616c3b4b68b5842bc899b509349a2c9a67312d574a726b048c0ea46dd4fcc45d8';
$AlpineRepoCommit = '3b749b4a926cd6db8c9f9f65b71d2f94e3fb08e5';

$S6OverlayVer = '1.19.1.1';
?>
# AUTOMATICALLY GENERATED
# DO NOT EDIT THIS FILE DIRECTLY, USE /Dockerfile.tmpl.php

<? if ($isAlpineImage) { ?>
# https://hub.docker.com/_/alpine
FROM alpine:3.6
<? } else { ?>
<? } ?>

MAINTAINER Instrumentisto Team <developer@instrumentisto.com>


# Build and install OpenDKIM
<? if ($isAlpineImage) { ?>
# https://git.alpinelinux.org/cgit/aports/tree/community/opendkim/APKBUILD?h=<?= $AlpineRepoCommit."\n"; ?>
RUN apk update \
 && apk upgrade \
 && apk add --no-cache \
        ca-certificates \
 && update-ca-certificates \

 # Install OpenDKIM dependencies
 && apk add --no-cache \
        libressl2.5-libcrypto libressl2.5-libssl \
        libmilter \
        # Perl and LibreSSL required for opendkim-* utilities
        libressl perl \

 # Install tools for building
 && apk add --no-cache --virtual .tool-deps \
        curl coreutils autoconf g++ libtool make \

 # Install OpenDKIM build dependencies
 && apk add --no-cache --virtual .build-deps \
        libressl-dev \
        libmilter-dev \

 # Download and prepare OpenDKIM sources
 && curl -fL -o /tmp/opendkim.tar.gz \
         https://downloads.sourceforge.net/project/opendkim/opendkim-<?= $OpenDKIMVer; ?>.tar.gz \
 && (echo "<?= $OpenDKIMSha512Sum; ?>  /tmp/opendkim.tar.gz" \
         | sha512sum -c -) \
 && tar -xzf /tmp/opendkim.tar.gz -C /tmp/ \
 && cd /tmp/opendkim-* \

 # Build OpenDKIM from sources
 && ./configure \
        --prefix=/usr \
        --sysconfdir=/etc/opendkim \
        # No documentation included to keep image size smaller
        --docdir=/tmp/opendkim/doc \
        --htmldir=/tmp/opendkim/html \
        --infodir=/tmp/opendkim/info \
        --mandir=/tmp/opendkim/man \
 && make \

 # Create OpenDKIM user and group
 && addgroup -S -g 91 opendkim \
 && adduser -S -u 90 -D -s /sbin/nologin \
            -H -h /run/opendkim \
            -G opendkim -g opendkim \
            opendkim \
 && addgroup opendkim mail \

 # Install OpenDKIM
 && make install \
 # Prepare run directory
 && install -d -o opendkim -g opendkim /run/opendkim/ \
 # Preserve licenses
 && install -d /usr/share/licenses/opendkim/ \
 && mv /tmp/opendkim/doc/LICENSE* \
       /usr/share/licenses/opendkim/ \
 # Prepare configuration directories
 && install -d /etc/opendkim/conf.d/ \

 # Cleanup unnecessary stuff
 && apk del .tool-deps .build-deps \
 && rm -rf /var/cache/apk/* \
<? } ?>
           /tmp/*


# Install s6-overlay
<? if ($isAlpineImage) { ?>
RUN apk add --update --no-cache --virtual .tool-deps \
        curl \
 && curl -fL -o /tmp/s6-overlay.tar.gz \
         https://github.com/just-containers/s6-overlay/releases/download/v<?= $S6OverlayVer; ?>/s6-overlay-amd64.tar.gz \
 && tar -xzf /tmp/s6-overlay.tar.gz -C / \

 # Cleanup unnecessary stuff
 && apk del .tool-deps \
 && rm -rf /var/cache/apk/* \
<? } ?>
           /tmp/*

ENV S6_BEHAVIOUR_IF_STAGE2_FAILS=2 \
    S6_CMD_WAIT_FOR_SERVICES=1


COPY rootfs /

RUN chmod +x /etc/services.d/*/run


EXPOSE 8891

ENTRYPOINT ["/init"]

CMD ["opendkim", "-f"]
