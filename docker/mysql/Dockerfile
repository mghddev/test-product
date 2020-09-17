FROM mysql:5.7.29

ARG UID
ARG GID

LABEL maintainer="Mohammad Ghaderi <mghddev@gmail.com>"

RUN usermod -u ${UID} mysql && \
    groupmod -g ${GID} mysql
