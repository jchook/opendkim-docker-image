<?php
$var = getopt('', ['version:', 'dockerfile:']);
$isAlpineImage = $var['dockerfile'] === 'alpine';
$AlpineRepoCommit = '4322107ba395a710a041ee479c5805c97169a36b';
?>
# AUTOMATICALLY GENERATED
# DO NOT EDIT THIS FILE DIRECTLY, USE /Dockerfile.tmpl.php

<? if ($isAlpineImage) { ?>
# https://hub.docker.com/_/alpine
FROM alpine:3.18
<? } else { ?>
# https://hub.docker.com/_/debian
FROM debian:bullseye-slim
<? } ?>

ARG opendkim_ver=<?= explode('-', $var['version'])[0].'-'.explode('-', $var['version'])[1]."\n"; ?>
ARG s6_overlay_ver=3.1.5.0


# Build and install OpenDKIM
<? if ($isAlpineImage) { ?>
# https://git.alpinelinux.org/cgit/aports/tree/community/opendkim/APKBUILD?h=<?= $AlpineRepoCommit."\n"; ?>
RUN apk update \
 && apk upgrade \
 && apk add --no-cache \
        ca-certificates \
<? } else { ?>
RUN apt-get update \
 && apt-get upgrade -y \
 && apt-get install -y --no-install-recommends --no-install-suggests \
            inetutils-syslogd \
            ca-certificates \
<? } ?>
 && update-ca-certificates \
    \
 # Install OpenDKIM dependencies
<? if ($isAlpineImage) { ?>
 && apk add --no-cache --force \
        libcrypto3 libssl3 \
        libmilter \
        # Perl and OpenSSL required for opendkim-* utilities
        openssl perl \
        mariadb-connector-c \
        postgresql-libs \
<? } else { ?>
 && apt-get install -y --no-install-recommends --no-install-suggests \
            libssl1.1 \
            libmilter1.0.1 \
            libbsd0 \
            libmariadb3 \
            libpq5 \
<? } ?>
    \
 # Install tools for building
<? if ($isAlpineImage) { ?>
 && apk add --no-cache --virtual .tool-deps \
        autoconf automake curl g++ libtool make \
<? } else { ?>
 && toolDeps=" \
        autoconf automake curl g++ libtool make pkg-config \
    " \
 && apt-get install -y --no-install-recommends --no-install-suggests \
            $toolDeps \
<? } ?>
    \
 # Install OpenDKIM + OpenDBX build dependencies
<? if ($isAlpineImage) { ?>
 && apk add --no-cache --virtual .build-deps \
        openssl-dev \
        libmilter-dev \
        db-dev \
        mariadb-dev \
        postgresql-dev \
        readline-dev \
<? } else { ?>
 && buildDeps=" \
        libssl-dev \
        libmilter-dev \
        libbsd-dev \
        libdb-dev \
        libreadline-dev \
        default-libmysqlclient-dev \
        libpq-dev \
    " \
 && apt-get install -y --no-install-recommends --no-install-suggests \
            $buildDeps \
<? } ?>
    \
 # Download and prepare OpenDBX sources
 && curl -fL -o /tmp/opendbx.tar.gz \
           http://linuxnetworks.de/opendbx/download/opendbx-1.4.6.tar.gz \
 && tar -xzf /tmp/opendbx.tar.gz -C /tmp/ \
 && cd /tmp/opendbx-* \
    \
 # Build OpenDBX from sources
 && export CXXFLAGS="-std=c++14" \
 && CPPFLAGS="-I/usr/include/mysql -I/usr/include/postgresql" ./configure --with-backends="mysql pgsql" \
 && make install \
    \
 # Download and prepare OpenDKIM sources
 && curl -fL -o /tmp/opendkim.tar.gz \
         https://github.com/trusteddomainproject/OpenDKIM/archive/refs/tags/${opendkim_ver}.tar.gz \
 && tar -xzf /tmp/opendkim.tar.gz -C /tmp/ \
 && cd /tmp/OpenDKIM-* \
    \
 # Build OpenDKIM from sources
 && autoreconf -i \
 && ./configure \
        --prefix=/usr \
        --sysconfdir=/etc/opendkim \
        --with-odbx \
        --with-openssl \
        --with-sql-backend \
        # No documentation included to keep image size smaller
        --docdir=/tmp/opendkim/doc \
        --htmldir=/tmp/opendkim/html \
        --infodir=/tmp/opendkim/info \
        --mandir=/tmp/opendkim/man \
 && make \
    \
 # Create OpenDKIM user and group
<? if ($isAlpineImage) { ?>
 && addgroup -S -g 91 opendkim \
 && adduser -S -u 90 -D -s /sbin/nologin \
            -H -h /run/opendkim \
            -G opendkim -g opendkim \
            opendkim \
 && addgroup opendkim mail \
<? } else { ?>
 && addgroup --system --gid 91 opendkim \
 && adduser --system --uid 90 --disabled-password --shell /sbin/nologin \
            --no-create-home --home /run/opendkim \
            --ingroup opendkim --gecos opendkim \
            opendkim \
 && adduser opendkim mail \
<? } ?>
    \
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
    \
 # Cleanup unnecessary stuff
<? if ($isAlpineImage) { ?>
 && apk del .tool-deps .build-deps \
 && rm -rf /var/cache/apk/* \
<? } else { ?>
 && apt-get purge -y --auto-remove \
                  -o APT::AutoRemove::RecommendsImportant=false \
            $toolDeps $buildDeps \
 && rm -rf /var/lib/apt/lists/* \
           /etc/*/inetutils-syslogd \
<? } ?>
           /tmp/*


# Install s6-overlay
<? if ($isAlpineImage) { ?>
RUN apk add --update --no-cache --virtual .tool-deps \
        curl \
<? } else { ?>
RUN apt-get update \
 && apt-get install -y --no-install-recommends --no-install-suggests \
            curl xz-utils \
<? } ?>
 && curl -fL -o /tmp/s6-overlay-noarch.tar.xz \
         https://github.com/just-containers/s6-overlay/releases/download/v${s6_overlay_ver}/s6-overlay-noarch.tar.xz \
 && curl -fL -o /tmp/s6-overlay-bin.tar.xz \
         https://github.com/just-containers/s6-overlay/releases/download/v${s6_overlay_ver}/s6-overlay-x86_64.tar.xz \
 && tar -xf /tmp/s6-overlay-noarch.tar.xz -C / \
 && tar -xf /tmp/s6-overlay-bin.tar.xz -C / \
<? if (!$isAlpineImage) { ?>
    \
 # Fix syslogd path
 && ln -s /usr/sbin/syslogd /sbin/syslogd \
<? } ?>
    \
 # Cleanup unnecessary stuff
<? if ($isAlpineImage) { ?>
 && apk del .tool-deps \
 && rm -rf /var/cache/apk/* \
<? } else { ?>
 && apt-get purge -y --auto-remove \
                  -o APT::AutoRemove::RecommendsImportant=false \
            curl xz-utils \
 && rm -rf /var/lib/apt/lists/* \
<? } ?>
           /tmp/*

ENV S6_KEEP_ENV=1 \
    S6_BEHAVIOUR_IF_STAGE2_FAILS=2 \
    S6_CMD_WAIT_FOR_SERVICES=1


COPY rootfs /

RUN chmod +x /etc/s6-overlay/s6-rc.d/*/run \
             /etc/s6-overlay/s6-rc.d/*/*.sh


EXPOSE 8891

ENTRYPOINT ["/init"]

CMD ["opendkim", "-f"]
