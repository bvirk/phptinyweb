### Some setup in ubuntu 18.04 

- applications indicating usage 
    - apache 2
    - php 7.2
    - mariadb
    - sshd
    - vsftpd

#### Error reporting in php

    ubuntu:$ cat /etc/php/7.2/apache2/php.ini |grep error|grep -v ";"
    
    disable_functions = pcntl_alarm,pcntl_fork,pcntl_waitpid,pcntl_wait,pcntl_wifexited,pcntl_wifstopped,pcntl_wifsignaled,pcntl_wifcontinued,pcntl_wexitstatus,pcntl_wtermsig,pcntl_wstopsig,pcntl_signal,pcntl_signal_get_handler,pcntl_signal_dispatch,pcntl_get_last_error,pcntl_strerror,pcntl_sigprocmask,pcntl_sigwaitinfo,pcntl_sigtimedwait,pcntl_exec,pcntl_getpriority,pcntl_setpriority,pcntl_async_signals,
    error_reporting = E_ALL & ~E_DEPRECATED & ~E_STRICT
    display_errors = On
    display_startup_errors = On
    log_errors = On
    log_errors_max_len = 1024
    ignore_repeated_errors = Off
    html_errors = On

#### ssh connection - same user name and password on each computer

- ensure sshd is running on ubuntu

#

    ubuntu:$ ps -A|grep ssh
    1256 ?         00:00:03 ssh-agent
    1547 ?         00:00:00 ssh-agent
    7566 ?         00:00:00 sshd
    7654 ?         00:00:00 sshd
    24665 ?        00:00:00 sshd

- connecting mashine    
    - delete possible old ~/.ssh/known_hosts
    - setup a connection
        - $ ssh-keygen
    
#### ftp connction using vsftpd on ubuntu

    ubuntu:$ cat /etc/vsftpd.conf
    
    listen=NO
    listen_ipv6=YES
    anonymous_enable=NO
    local_enable=YES
    write_enable=YES
    local_umask=022
    dirmessage_enable=YES
    use_localtime=YES
    xferlog_enable=YES
    connect_from_port_20=YES
    #chroot_local_user=NO
    #secure_chroot_dir=/var/run/vsftpd/empty
    pam_service_name=vsftpd
    #rsa_cert_file=/etc/ssl/certs/ssl-cert-snakeoil.pem
    #rsa_private_key_file=/etc/ssl/private/ssl-cert-snakeoil.key
    #ssl_enable=YES
    pasv_enable=Yes
    pasv_min_port=10000
    pasv_max_port=10100
    allow_writeable_chroot=YES
    force_dot_files=YES

#### [enable mod rewrite](https://www.codeproject.com/questions/1037625/url-rewriting-in-php)

     ubuntu:$ ls -l /etc/apache2/mods-enabled/rewrite.load 
     lrwxrwxrwx 1 root root 40 Jun 25 17:47 /etc/apache2/mods-enabled/rewrite.load -> /etc/apache2/mods-available/rewrite.load

 
  