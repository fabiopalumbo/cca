<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SentEmailStatus extends Model
{
    protected $table = 'sent_emails_status';

    protected $fillable = array(
	    'sent_email_id',
        'event',
	    'reason',
	    'timestamp'
    );
}
