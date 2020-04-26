# Walk Through
- [x] **Modify the source**
- To prevent someone else from abusing your backdoor – a nightmare scenario while pentesting – you need to modify the source code to indicate where you want the reverse shell thrown back to.  Edit the following lines of php-reverse-shell.php:
______________
| Variable      | Default                | Personal?                       |
| ------------- |:----------------------:| -------------------------------:|
| $ip           | __170.0.0.1__          | Edit __line 7__                 |
| $port         | __1234__               | Edit __line 4__                 |
______________
