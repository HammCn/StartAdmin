<p align="center">
<img width="500" src="https://images.gitee.com/uploads/images/2020/0408/002846_2e9986dd_145025.png"/>
</p>
<p align="center">
<a href="https://gitee.com/hamm/StartAdmin/stargazers" target="_blank"><img src="https://svg.hamm.cn/gitee.svg?type=star&user=hamm&project=StartAdmin"/></a>
<a href="https://gitee.com/hamm/StartAdmin/members" target="_blank"><img src="https://svg.hamm.cn/gitee.svg?type=fork&user=hamm&project=StartAdmin"/></a>
<img src="https://svg.hamm.cn/badge.svg?key=Base&value=ThinkPHP6"/>
<img src="https://svg.hamm.cn/badge.svg?key=Data&value=MySQL5.5"/>
<img src="https://svg.hamm.cn/badge.svg?key=Runtime&value=PHP7.1"/>
<img src="https://svg.hamm.cn/badge.svg?key=License&value=Apache-2.0"/>
</p>

### 介绍

StartAdmin，基于ThinkPHP6/ElementUI/VUE的后台管理二次开发脚手架 集成了微信接入、微信(登录)支付、第三方登录、权限管理，用户(组)管理，WEUI，菜单管理，节点管理，访问日志，访问统计，API生成，后台代码生成，Excel数据导出等常用功能，是轻量级后端脚手架。QQ群: 973087692


<a href="https://startadmin.hamm.cn/" target="_blank">使用手册</a> <a href="https://sa.hamm.cn/" target="_blank">体验DEMO</a>

> 温馨提示：由于demo站点我们给了最高权限的体验帐号，可能存在被恶意用户删除数据导致无法正常体验，你可以加QQ群```973087692```联系群主恢复数据后体验，当然，我们更建议你直接自己部署一套后体验。

### 使用说明
推荐使用Composer方式安装
```
执行  composer create-project hamm/start-admin StartAdmin
创建数据库(utf8-mb4)，导入数据库StartAdmin.sql
配置站点和ThinkPHP伪静态
复制.example.env到.env 修改自己的数据库配置
Good Start!
```
传统方式安装
```
创建数据库(utf8-mb4)，导入数据库StartAdmin.sql
Clone或下载本仓库zip包，配置站点和ThinkPHP伪静态
复制.example.env到.env 修改自己的数据库配置
Composer install 
Good Start!
```
### 配置说明
```
LNMP环境：

- PHP7.1+
- Nginx
- CentOS7
- MySQL5.5+
```
### 特色功能
```
基于用户组的RBAC权限控制
基于VUE+ElementUI的后端管理
基于WeUI+VUE的移动Web页面管理
集成EasyWechat的微信开发工具包
第三方登录(QQ/Weibo登录的DEMO)
后端代码生成 数据表与模型生成 API生成
Excel数据导出
```
### 感谢(以下排名不分先后)
``` 
Vue.js / ElementUI / WeUI / ThinkPHP6 / EasyWechat
```

### 参与贡献
```
1. Fork 本仓库
2. 新建分支 添加或修改功能
3. 提交代码
4. 新建 Pull Request
```
### 晒个截图
![StartAdmin](https://images.gitee.com/uploads/images/2020/0401/021330_e8b2482f_145025.png "截屏2020-04-0101.36.11.png")

![StartAdmin](https://images.gitee.com/uploads/images/2020/0401/021415_b9ec454e_145025.png "截屏2020-04-0101.38.26.png")