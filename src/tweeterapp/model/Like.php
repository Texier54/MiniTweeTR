<?php

	namespace tweeterapp\model;

	class Like extends \Illuminate\Database\Eloquent\Model {

		protected $table = 'like';
		protected $primaryKey = 'id';
		public $timestamps = false;

	}
