<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;
use Illuminate\Notifications\Notifiable;
use App\Models\Traits\FullTextSearch;

class FlutterArtifact extends Model implements Feedable
{
    use Notifiable;
    use FullTextSearch;

    protected $fillable = [
        'title',
        'slug',
        'url',
        'comment',
        'published_date',
        'contents',
        'type',
        'image_url',
        'source_url',
        'repo_url',
        'is_approved',
        'is_visible',
        'meta_description',
        'meta_publisher',
        'meta_author',
        'meta_author_url',
        'meta_author_twitter',
        'meta_publisher_twitter',
    ];

    protected $hidden = [
        'is_visible',
        'is_approved',
    ];

    protected $appends = [
        'pretty_published_date',
        'pretty_type',
        'type_class',
        'type_label',
        'domain',
    ];

    protected $searchable = [
        'contents',
        'title',
        'comment',
        'meta_author',
        'meta_publisher',
        'meta_description',
        'meta_author_twitter',
        'meta_publisher_twitter',
    ];

    public function url()
    {
        return url((isIAW() ? 'flutterx/' : '') . $this->slug);
    }

    public function editUrl()
    {
        return $this->url() . '/edit';
    }

    public function link()
    {
        return link_to($this->url(), $this->event_name, ['target' => '_blank']);
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function scopeVisible($query)
    {
        $query->where('is_visible', '=', true);
    }

    public function scopeApproved($query)
    {
        $query->visible()->where('is_approved', '=', true);
    }

    public function scopeOwnedBy($query, $userId)
    {
        $query->where('user_id', '=', $userId);
    }


    public function getPrettyPublishedDateAttribute()
    {
        $time = strtotime($this->published_date);

        return date('D, F jS Y', $time);
    }

    public function getPrettyTypeAttribute()
    {
        return ucwords($this->type);
    }

    public function getDomainAttribute()
    {
        $parse = parse_url($this->url);

        $host = $parse['host'];
        $host = str_replace(['www.', 'blog.'], '', $host);

        return $host;
    }

    public function getTypeClassAttribute()
    {
        if ($this->type == 'article') {
            return 'link';
        } elseif ($this->type == 'video') {
            return 'primary';
        } elseif ($this->type == 'library') {
            return 'dark';
        }
    }

    public function getTypeLabelAttribute()
    {
        if ($this->type == 'article') {
            return 'READ ARTICLE';
        } elseif ($this->type == 'video') {
            return 'WATCH VIDEO';
        } elseif ($this->type == 'library') {
            return 'VIEW LIBRARY';
        }
    }

    public function toFeedItem()
    {
        /*
        return FeedItem::create()
            ->id($this->slug)
            ->title($this->title)
            ->summary($this->short_description)
            ->updated($this->updated_at)
            ->link('/flutter-app/' . $this->slug)
            ->author($this->title);
        */
    }
}
