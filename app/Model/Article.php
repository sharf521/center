<?php
namespace App\Model;

class Article extends Model
{
    protected $table = 'article';
    
    public function ArticleData()
    {
        return $this->hasOne('article_data','id','id');
    }

    /**
     * @return Category
     */
    public function Category()
    {
        return $this->hasOne('App\Model\Category','id','category_id');
    }
}