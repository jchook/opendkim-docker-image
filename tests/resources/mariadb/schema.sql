CREATE TABLE IF NOT EXISTS `dkim_keys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain` varchar(255) NOT NULL,
  `selector` varchar(255) NOT NULL,
  `private_key` text NOT NULL,
  `public_key` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `domain_selector` (`domain`,`selector`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `dkim_keys`
(`id`, `domain`, `selector`, `public_key`, `private_key`) VALUES
( 1, 'isi.edu', 'myselector',
  'v=DKIM1;h=sha256;k=rsa;p=MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA8Sk09e7toaBcrcBy3Rw8P4Qe4WDqj2qXl5qIvfWHFfrhJ7eBEBf1w/ej6FTnKWRstH3xZJzEjJAGa+Zl7+EugBECJ/tBZyneTYagfY+KBH2JXqaxbwybRX1vfbBuRSbTaYINoiQYncBoF5bv+rXpOWTIpVR9TMh/7+IYJDS4nPo8HdmevZyhLF0YcY0pVljfZDjtIzbJu6KPpENNp/wVqrfpLGi8y0EPf+bAE9EcvBbpX0nK9Gf3HJ1M7ot+VCIAiLsZoyX63g/TSXn6FBllsJK7iEWPT+556JaunVCkLinzZloNVgsxsi/1GbGGZ9F4E4eTALo+PUHltzsNhwML+QIDAQAB',
  '-----BEGIN RSA PRIVATE KEY-----
MIIEpAIBAAKCAQEA8Sk09e7toaBcrcBy3Rw8P4Qe4WDqj2qXl5qIvfWHFfrhJ7eB
EBf1w/ej6FTnKWRstH3xZJzEjJAGa+Zl7+EugBECJ/tBZyneTYagfY+KBH2JXqax
bwybRX1vfbBuRSbTaYINoiQYncBoF5bv+rXpOWTIpVR9TMh/7+IYJDS4nPo8Hdme
vZyhLF0YcY0pVljfZDjtIzbJu6KPpENNp/wVqrfpLGi8y0EPf+bAE9EcvBbpX0nK
9Gf3HJ1M7ot+VCIAiLsZoyX63g/TSXn6FBllsJK7iEWPT+556JaunVCkLinzZloN
Vgsxsi/1GbGGZ9F4E4eTALo+PUHltzsNhwML+QIDAQABAoIBAQCK0qu3/sgwSD3R
j30Pp1dPQOD94GpmEHgfP0gEAbi3gGnoEQxslT5WhtGFxoojG7ov1GgHAO9r8uSf
bEu14KyB8EiLd2lY15MukZcFcIGEwDc5kz+Eq38ea2yor81qZUVB8Smj8p9w//dr
zOlsKxeMgGW4NoDCmJB9KGu5O9giFBRAMYqKMBdETBX16Ry3YOcA8moH+y6WTLFM
fuRZNb9r3k5bSqMlARqKGRojI5jRAKkgcGq0cjstXHvXuIJ6PkSIfmR1eJK1BB9x
AH9JhXkGBaIxKVxyPMrGW9U85rYDCvnfA+9P8QB3Ymup0FfkxE/c8vbEo49RscnR
MmCRF1WZAoGBAPp6MM5kKhBz9YrCp64ZZlwPQB3pFvc3xMTq5X2EfLdv8k6sulLk
dsP8GtdWo7hLQ9je6CpDoV0526L37enZ/ELeVBidg28T+mHVpyZmiHkOaRNpxozv
GKE8XVyH6mJvc3hShYKF7cVF5/Fu4tvwgG/bQEcln74nvnJCMbX9TwGnAoGBAPZ6
bjT79mOv9C4V1Euqnx0A5nDHrkridwRvkChhULRwSUzb00jbdM+ZNwAl85B2ddBC
iffXU4DgTWbkMlKfds/fnC809ynVMXceIhwfbRr4WeN2SyDBDJZM005cKWxHpJnR
KwuGu0H+5vh/Cf00SQjBD3p9igEgmArXToSH+vlfAoGBAMePyQj3hwgay3wlwKEW
fZZFVElAZ9rJ5Q0bNYK+pvsjxwNl3QGkIvfdCamdzs1Lsh+84W6i+ZKkQVjjfft+
gNzp9Ei0Xn6GhUujhQw0TvFcSN19vgKVkKMNzDin1VdeArrPzK2EdT9ihfy24ypm
wH3eSqUk1dQUKEkychbUXjkrAoGAcMJl9LtoZwayMPsvmkY+cKhexC214PBl/pOD
YdXTdBkCj7TpniU39Vlkvh6epPJsx5AJSmcp/oWfI3k2RHJLiqID89zJTkwISzRv
6mm+Il6H0PXnPN7UgVY4PVsQYEcOWIhGpwlGVdTlmb2Utk/bMbQQ/rq8DfP5WsxR
UXRcLwUCgYBu3bBgbE+6MfVm1m/z2eaPkCEsAbE6CK+3MMgtOEQtaS2tXTU2LJvB
5eeOY4YCYl0Il6ZK1TEj3erv0gOoUz9X0udGngLpTsE4IRg1LG53xFF5s1XnmkBZ
LTK4XohvCk0MAaw1rCHAHwSOq9t+DOYO1jeTyCV/M0orSpDMcrruFA==
-----END RSA PRIVATE KEY-----
'
);


