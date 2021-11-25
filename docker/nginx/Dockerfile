#FROM node:8 as npm-builder
#
#WORKDIR /usr/src/app
##
##RUN npm install --global gulp-cli
#COPY package.json /usr/src/app
#RUN npm install

#COPY gulpfile.js ./
#COPY ./public/static/common ./public/static/common
#
#RUN npm run build
###

FROM rnburn/nginx-opentracing:0.2.1

ARG environment=prod

ADD https://github.com/Yelp/dumb-init/releases/download/v1.2.0/dumb-init_1.2.0_amd64 /usr/local/bin/dumb-init
RUN chmod +x /usr/local/bin/dumb-init

RUN apt-get update && \
    apt-get install -y --no-install-recommends && \
    apt-get -y install curl \
            geoip-database \
            nginx-module-geoip=1.13.8-1~stretch
#    apt-get install nodejs npm
RUN curl -sL https://deb.nodesource.com/setup_14.x | bash -
RUN apt-get install -y nodejs
RUN apt-get install -y npm

COPY docker/nginx/default_${environment}.conf /etc/nginx/conf.d/default.conf
COPY docker/nginx/nginx.conf /etc/nginx/

RUN mkdir -p /var/www/

COPY ./public /usr/src/app/public
COPY package.json /usr/src/app
ADD https://raw.githubusercontent.com/rlerdorf/opcache-status/master/opcache.php /usr/src/app/public

# Rewrite signal. Kubernetes always sends SIGTERM signal, nginx use SIGQUIT for graceful shutdown.
# http://nginx.org/ru/docs/control.html
ENTRYPOINT ["dumb-init", "--rewrite", "15:3"]

CMD ["nginx", "-g", "daemon off;"]