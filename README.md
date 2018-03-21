#Lessons-grabber 正方教务系统抢课助手 :
---
&nbsp;&nbsp;&nbsp;&nbsp;一个php小程序，可以用来代理登录大部分中国大学的正方教务系统，用于抢体育课，其中还包括了一个学生身份的认证的程序。
&nbsp;&nbsp;&nbsp;&nbsp;a php function which can help you grab the P.E. class in most china's university zfsoft website,which include a student's id identification function.
---
##Demo version 功能演示版 完成时间：2015.02.01
![login][1]

>代理登录，已经可以切换学校的内网接口和电信接口了，如果需要的话也可以接入其他正方教务系统的地址。可以获得学生姓名及教务系统存储的其他信息

>Login to the school as your agent ,now  it can toggle the access point of our  school's Internal Network and the telecommunications network , and also it can use the access point of other school if you need .in addition ,it can get the information of the student which in the school's website . 


![check-score][2]

>查询成绩，并且支持排序

>Inquiry your grades, and it supports sorting the result.

主要功能：
![get-PE][3]

>保存身份验证信息，来实现绕过学校的刷新限制，快速发送大量课程请求到队列中，实现抢课.

>it can save your identification information to bypass the limit of the refresh frequency,so it can send  a large number requirement  in a short time ,which can help you grab the class you want.


## Query results version 查询成绩版（附带界面）完成时间：2016.07.03

>详细说明见文件夹README.md
>More details on the README.md in the floder

  [1]: login.gif
  [2]: checkscore.gif
  [3]: getPE.gif