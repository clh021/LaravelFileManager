# docker build -t hub.linakesi.com/webdev/lfm .
# 项目开发测试：docker run --name lfm -d -p 80:80 -v /home/lee-working/projects/www:/var/www hub.linakesi.com/webdev/lfm
# 目录挂载测试(public)：docker run --name lfm -d -p 80:80 -v /lfmRoot:/var/www/storage/app/public hub.linakesi.com/webdev/lfm
# 目录挂载需要注意创建好存在的目录
# http://${serverDomain}/file-manager/content?disk=public
# http://${serverDomain}/file-manager/tree?disk=public
# public 可以理解为起始根目录 增加新的起始根目录需要修改项目配置重新打 image。
# 有关 header 支持不同用户起始目录的支持稍后添加推送新的 image。
FROM leehom/docker.home:fpm-08215
MAINTAINER leehom Chen <clh021@gmail.com>
LABEL maintainer="leehom Chen <clh021@gmail.com>"
ADD ./www /var/www
WORKDIR /var/www
CMD php ./artisan serve --port=80 --host=0.0.0.0
EXPOSE 80
HEALTHCHECK --interval=1m CMD curl -f http://localhost/ || exit 1