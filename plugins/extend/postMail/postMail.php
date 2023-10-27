<?php 

/* ----  kontrola jadra  ---- */ 
if(!defined('_core')) die; 

/* ---- funkce pluginu ---- */ 
function _postMail($args) 
{ 
 $mailtext = "Dobrý den,\nna webové stránky "._title." ("._url.")";
 $odesilat = false;
 
 if($args['posttype'] == 1 || $args['posttype'] == 3 || $args['posttype'] == 5)
 {
  $title = DB::result(DB::query("SELECT title FROM `"._mysql_prefix."-root` WHERE id=".$args['posttarget']));
  
  if($args['posttype'] == 1) $mailtext .= " byl k sekci ".$title." přidán nový příspěvek s předmětem ".$args['subject'];
  if($args['posttype'] == 3) $mailtext .= " byl ke knize ".$title." přidán nový příspěvek s předmětem ".$args['subject'];
  if($args['posttype'] == 5) $mailtext .= " bylo k fóru ".$title." přidáno nové téma s předmětem ".$args['subject'];
  
  $odesilat = true;
 }
 if($args['posttype'] == 2)
 {
  $title = DB::result(DB::query("SELECT title FROM `"._mysql_prefix."-articles` WHERE id=".$args['posttarget']));
  $mailtext .= " byl k článku ".$title." přidán nový komentář s předmětem ".$args['subject'];
  $odesilat = true;
 }

 if($odesilat)
 {
  if($args['author'] == -1) $mailtext .= " od hosta ".$args['guest'].".\n";
  else
  {
   $user = DB::result(DB::query("SELECT username FROM `"._mysql_prefix."-users` WHERE id=".$args['author']));
   $mailtext .= " od uživatele ".$user.".\n";
  }

  for($i = 0; $i < 30; $i++)
   $mailtext .= "-";
 
  $mailtext .= "\n".$args['text'];   
 
  //    "KOMU"  , "PŘEDMĚT"               , "TEXT"   , "OD"
  _mail(_sysmail, "Nový příspěvek na webu", $mailtext, "From: "._sysmail);
 }
}

/* ---- registrace pluginu ---- */  
_extend("reg", "posts.submit", "_postMail");