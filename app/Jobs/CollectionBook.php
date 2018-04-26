<?php

namespace App\Jobs;

use App\Article;
use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CollectionBook extends Job implements SelfHandling,ShouldQueue
{
    use InteractsWithQueue,SerializesModels;

    public $articleId = "";
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($articleId)
    {
        //
        $this->articleId = $articleId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //

        $handle = fopen("D://bbbbbbbbb.txt","a");
//        fwrite($handle,time());
//        fclose($handle);
        $articleInfo = Article::where("id",$this->articleId)->first();
        $fix = "D:/WWW/laravel-v5.1.11/public";
        $fileName = "/upload/articleimg/thumb/".date("Ymd",time())."/".date("YmdHis",time()).mt_rand(100,999).".jpg";
        $ffmpegUrl = "$fix/ffmpeg/bin/ffmpeg";
        $videoUrl = $fix.$articleInfo->article_video; //fwrite($handle,$videoUrl);fclose($handle);
//        $videoUrl = "D:/WWW/laravel-v5.1.11/public/upload/articleimg/video/1.mp4";
        $fileName2 = $fix . $fileName;
        exec("$ffmpegUrl -i $videoUrl -r 1 -ss 0:0:1 -t 0:0:1 -f image2 $fileName2",$arrOut);
        $articleInfo->article_thumb = $fileName;
        $articleInfo->save();
        fwrite($handle,print_r($articleInfo,1));
        fclose($handle);
    }
}
