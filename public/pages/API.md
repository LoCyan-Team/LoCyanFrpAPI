# LoCyan Frp API 接口文档

## 获取用户登录Token

>url:`https://api.freenat.gq/User/DoLogin?username=[账号]&password=[密码]`

!> 注意，此操作类似于登录，在进行接下来的操作之前，请务必执行此接口获取token！

返回值：
``` javascript
{
	"status": 0,
	"message": "登录成功",
	"token": "618d016e2f48bf75b4526dc3f46303c4"
}
```
返回值释义

| 变量 | 释义 |
| -- | -- |
| status | 0：成功&nbsp;&nbsp;&nbsp;&nbsp;-1：账号或密码错误，或者账号不存在 |
| message | 提示语 |
| token | 用户token，会被网站存放于数据库中用于用户操作与鉴权，每次登录都将刷新token |

## 获取用户信息

>url:`https://api.freenat.gq/Account/info?username=[账号]`

返回值：
``` javascript
{
	"status": 0,
	"username": "daiyangcheng",
	"email": "daiyangcheng@qq.com",
	"token": "*************************"
}
```
返回值释义

| 变量 | 释义 |
| -- | -- |
| status | 0：成功&nbsp;&nbsp;&nbsp;&nbsp;-1：账号不存在&nbsp;&nbsp;&nbsp;&nbsp;-2：未提供用户名导致错误 |
| username | 用户名 |
| email | 用户邮箱 |
| token | 此token非彼token，此token为内网映射的身份验证token，而不是登录后返回的账号操作token |

## 通过登录Token获取用户名

>url:`https://api.freenat.gq/Account/GetUserName?token=[TOKEN]`

返回值：
``` javascript
{
	"status": 0,
	"message": "查询成功",
	"username": "daiyangcheng"
}
```
返回值释义

| 变量 | 释义 |
| -- | -- |
| status | 0：成功&nbsp;&nbsp;&nbsp;&nbsp;-1：token不存在 |
| message | 提示语 |
| username | 用户名 |