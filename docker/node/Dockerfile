FROM node:latest

# Switch to root user
USER root

# APPLICATION
RUN mkdir /app
WORKDIR /app
COPY scripts/* /bin/ 
RUN chmod +x /bin/start

# VUE-CLI
RUN yarn global add @vue/cli -g

# Logs
RUN touch /var/log/front.log

# ClEAN
RUN apt-get autoremove -y && apt-get autoclean -y 

# RUN APPLICATION
CMD start ; tail -f /var/log/front.log