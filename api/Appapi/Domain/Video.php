<?php

class Domain_Video {

	public function getVideoList($uid,$p) {
        $rs = array();

        $model = new Model_Video();
        $rs = $model->getVideoList($uid,$p);

        return $rs;
    }

	public function getVideo($uid,$videoid) {
        $rs = array();

        $model = new Model_Video();
        $rs = $model->getVideo($uid,$videoid);

        return $rs;
    }

    public function getRecommendVideos($uid,$p){
        $rs = array();

        $model = new Model_Video();
        $rs = $model->getRecommendVideos($uid,$p);

        return $rs;
    }


    public function getNearby($uid,$lng,$lat,$p){
        $rs = array();

        $model = new Model_Video();
        $rs = $model->getNearby($uid,$lng,$lat,$p);
        
        return $rs;
    }
}
