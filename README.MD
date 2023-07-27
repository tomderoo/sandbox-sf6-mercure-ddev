# Symfony 6 - Mercure - DDEV sandbox

## Setup

- Have DDEV 1.22.0
- Run `ddev start`
- Run `ddev composer install`
- You now have a running Symfony 6.3 with a Mercure hub on HTTPS port 10002

## Operation

- Visit `https://sandbox-sf6.ddev.site/` in your modern browser
- Open your inspection window to check the console; this will show you any connection errors in more detail
- When connection to your local Mercure publisher hub works, then you should not see an error
- When no error, run `ddev php bin/console app:mercure:run` on your command line; this will start a command that will push an update every second
- Watch your browser where a block should appear that changes color every second (i.e. with every update)
- When there is an error, it _may_ be a CORS error in your network tab, but this is generic because the response did not provide a correct header structure - visit `https://sandbox-sf6.ddev.site:10002/.well-known/mercure?topic=whatever` to see the real problem: a 502 Bad Request

## DDEV config: the difference between error or not

The following DDEV global config works (i.e. with deprecated `nginx-proxy` router):

```shell
Global configuration: 
instrumentation-opt-in=true 
omit-containers=[] 
web-environment=[] 
performance-mode=none 
router-bind-all-interfaces=false 
internet-detection-timeout-ms=3000 
disable-http2=false 
use-letsencrypt=false 
letsencrypt-email= 
table-style=default 
simple-formatting=false 
auto-restart-containers=false 
use-hardened-images=false 
fail-on-hook-fail=false 
required-docker-compose-version=v2.20.0 
use-docker-compose-from-path=false 
project-tld= 
xdebug-ide-location= 
no-bind-mounts=false 
router=nginx-proxy 
wsl2-no-windows-hosts-mgt=false 
router-http-port=80 
router-https-port=443 
```

The following DDEV global config does not work - the only change here is using the now default `traefik` router:

```shell
Global configuration: 
instrumentation-opt-in=true 
omit-containers=[] 
web-environment=[] 
performance-mode=none 
router-bind-all-interfaces=false 
internet-detection-timeout-ms=3000 
disable-http2=false 
use-letsencrypt=false 
letsencrypt-email= 
table-style=default 
simple-formatting=false 
auto-restart-containers=false 
use-hardened-images=false 
fail-on-hook-fail=false 
required-docker-compose-version=v2.20.0 
use-docker-compose-from-path=false 
project-tld= 
xdebug-ide-location= 
no-bind-mounts=false 
router=traefik 
wsl2-no-windows-hosts-mgt=false 
router-http-port=80 
router-https-port=443 
```

Remember to `ddev poweroff && ddev restart` between config changes to minimize any corruption of config changes.
And, of course, to have them in effect...