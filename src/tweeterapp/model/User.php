<?php

	namespace tweeterapp\model;

	class User extends \Illuminate\Database\Eloquent\Model {

		protected $table = 'user';
		protected $primaryKey = 'id';
		public $timestamps = false;

		public function tweets() {
			return $this->hasMany('\tweeterapp\model\Tweet', 'author');

			/* 'Tweet'     : le nom de la classe du modèle lié   */
       		/* 'author' : la clé étrangère dans la table liée */
		}


		public function likeIDo() {
		       return $this->belongsToMany('\tweeterapp\model\Tweet', 'like', 'user_id', 'tweet_id');

		       /* 'Tweet'        : le nom de la classe du model lié */
		       /* 'Like'          : le nom de la table pivot */
		       /* 'user_id'        : la clé étrangère de ce modèle dans la table pivot */
		       /* 'tweet_id'     : la clé étrangère du modèle lié dans la table pivot */
		}

		public function followIDo() {
		       return $this->belongsToMany('\tweeterapp\model\User', 'follow', 'follower', 'tweet_id');

		       /* 'User'        : le nom de la classe du model lié */
		       /* 'Follow'          : le nom de la table pivot */
		       /* 'follower'        : la clé étrangère de ce modèle dans la table pivot */
		       /* 'followee'     : la clé étrangère du modèle lié dans la table pivot */
		}

		public function followIHave() {
		       return $this->belongsToMany('\tweeterapp\model\User', 'follow', 'followee', 'follower');

		       /* 'User'        : le nom de la classe du model lié */
		       /* 'Follow'          : le nom de la table pivot */
		       /* 'followee'        : la clé étrangère de ce modèle dans la table pivot */
		       /* 'follower'     : la clé étrangère du modèle lié dans la table pivot */
		}

	}