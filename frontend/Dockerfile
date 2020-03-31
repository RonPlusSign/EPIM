FROM node:alpine

WORKDIR /usr/src/app

ENV PATH /usr/src/app/node_modules/.bin:$PATH

# Install app dependencies
# A wildcard is used to ensure both package.json AND package-lock.json are copied
# where available (npm@5+)
COPY package.json /usr/src/app/package.json

RUN npm install
RUN npm install -g @vue/cli
# If you are building your code for production
# RUN npm ci --only=production

CMD [ "npm", "run", "serve" ]
