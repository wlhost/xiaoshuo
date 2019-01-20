<<<<<<< HEAD
# 小涴熊小说CMS，基于ThinkPHP5.1开发。
- 域名/install，进行首次安装。为了安全，安装完成后最好把install目录删除。
- 将网站运行目录设置为public目录。
- 如果是NGINX，添加以下伪静态规则：
```
  if (!-e $request_filename) {  
      rewrite  ^(.*)$  /index.php?s=/$1  last;  
      break;  
	}  
```	
=======
# xiaoshuo
小说cms
>>>>>>> 0aa0a1a70474aa8f3ac81e9a844333173e82da8c
