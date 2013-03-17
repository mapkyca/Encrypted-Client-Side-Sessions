<?php


    require 'cookie.class.php';
    
    //session_set_save_handler(new ClientSideCookieSession(), true);
    session_set_save_handler(
        "ClientSideCookieSession::open_53",
        "ClientSideCookieSession::close_53",
        "ClientSideCookieSession::read_53",
        "ClientSideCookieSession::write_53",
        "ClientSideCookieSession::destroy_53",
        "ClientSideCookieSession::gc_53");
    session_start();
    
    
    session_write_close();
    
    echo "Got session date: " . $_SESSION['test'];
    