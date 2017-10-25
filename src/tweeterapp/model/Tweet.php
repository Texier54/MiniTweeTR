<?php

	namespace tweeterapp\model;

	class Tweet extends \Illuminate\Database\Eloquent\Model {

		protected $table = 'tweet';
		protected $primaryKey = 'id';
		public $timestamps = true;

		public function user() {
			return $this->belongsTo('\tweeterapp\model\User', 'author');

			/* 'User'     : le nom de la classe du modèle lié   */
       		/* 'author' : la clé étrangère dans la table liée */
		}

		public function likeIHave() {
       		return $this->belongsToMany('\tweeterapp\model\User', 'like', 'tweet_id', 'user_id');

		       /* 'User'          : le nom de la classe du model lié */
		       /* 'Like '         : le nom de la table pivot */

		       /* 'tweet_id'     : la clé étrangère de ce modèle dans la table pivot */
		       /* 'user_id'        : la clé étrangère du modèle lié dans la table pivot */
		}

	}
