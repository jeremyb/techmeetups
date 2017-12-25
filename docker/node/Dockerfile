FROM node:9-alpine

RUN mkdir -p /usr/src/client
WORKDIR /usr/src/client
COPY . ./
RUN yarn install
CMD yarn run encore dev --watch
