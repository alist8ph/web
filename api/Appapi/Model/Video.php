<?php

class Model_Video extends Model_Common {

	/* 热门视频 */
	public function getVideoList($uid,$p){


		$nums=20;
		$start=($p-1)*$nums;

		$videoids_s='';
		$where="isdel=0 and status=1";  //上架且审核通过
		
		$video=DI()->notorm->users_video
				->select("*")
				->where($where)
				->order("RAND()")
				->limit($start,$nums)
				->fetchAll();

		foreach($video as $k=>$v){
			
			$userinfo=$this->getUserInfo($v['uid']);
			if(!$userinfo){
				$userinfo['user_nicename']="已删除";
			}

			$video[$k]['userinfo']=$userinfo;
			$video[$k]['datetime']=$this->datetime($v['addtime']);	
			$video[$k]['comments']=$this->NumberFormat($v['comments']);	
			$video[$k]['likes']=$this->NumberFormat($v['likes']);	
			$video[$k]['steps']=$this->NumberFormat($v['steps']);	
			if($uid){
				$video[$k]['islike']=(string)round(0,1);;	
				$video[$k]['isstep']=(string)round(0,1);;	
				$video[$k]['isattent']=(string)round(0,1);;	
			}else{
				$video[$k]['islike']=0;	
				$video[$k]['isstep']=0;	
				$video[$k]['isattent']=0;	
			}

			$video[$k]['musicinfo']=$video[$k]['userinfo']['user_nicename'];	
			$video[$k]['thumb']=$this->get_upload_path($v['thumb']);
			$video[$k]['thumb_s']=$this->get_upload_path($v['thumb_s']);
			$video[$k]['href']=$this->get_upload_path($v['href']);

		}


		return $video;
	}		
	
	/* 视频详情 */
	public function getVideo($uid,$videoid){
		$video=DI()->notorm->users_video
					->select("*")
					->where("id = {$videoid}")
					->fetchOne();
		if(!$video){
			return 1000;
		}
		
		$video['userinfo']=$this->getUserInfo($video['uid']);	
		$video['isattent']=(string)round(0,1);;	
		$video['datetime']=$this->datetime($video['addtime']);	
		$video['comments']=$this->NumberFormat($video['comments']);	
		$video['likes']=$this->NumberFormat($video['likes']);	
		$video['steps']=$this->NumberFormat($video['steps']);	
		$video['shares']=$this->NumberFormat($video['shares']);	
		$video['islike']=(string)round(0,1);;			
		$video['isstep']=(string)round(0,1);;

		$video['musicinfo']=$video[$k]['userinfo']['user_nicename'];

		$video['thumb']=$this->get_upload_path($video['thumb']);	
		$video['thumb_s']=$this->get_upload_path($video['thumb_s']);	
		$video['href']=$this->get_upload_path($video['href']);
		
		
		return 	$video;
	}

	/*获取推荐视频列表*/
	public function getRecommendVideos($uid,$p){
		$pnums=20;
		$start=($p-1)*$pnums;


		//获取私密配置里的评论权重和点赞权重
		$configPri=$this->getConfigPri();
		$video_showtype=$configPri['video_showtype'];

		if($video_showtype==0){ //随机
			$info=DI()->notorm->users_video
			->where("isdel=0 and status=1")
			->order("rand()")
			->limit($start,$pnums)
			->fetchAll();

		}else{

			$comment_weight=$configPri['comment_weight'];
			$like_weight=$configPri['like_weight'];
			$share_weight=$configPri['share_weight'];

			$prefix= DI()->config->get('dbs.tables.__default__.prefix');

			$info=DI()->notorm->users_video
            ->select("*,(ceil(comments * ".$comment_weight." + likes * ".$like_weight." + shares * ".$share_weight.") + show_val)* if(format(watch_ok/views,2) >1,'1',format(watch_ok/views,2)) as recomend")
            ->where("isdel=0 and status=1")
            ->order("recomend desc,addtime desc")
            ->limit($start,$pnums)
            ->fetchAll();
		}


		if(!$info){
			return 1001;
		}


		foreach ($info as $k => $v) {
			$info[$k]['userinfo']=$this->getUserInfo($v['uid']);
			$info[$k]['datetime']=$this->datetime($v['addtime']);
			$info[$k]['thumb']=$this->get_upload_path($v['thumb']);
			$info[$k]['thumb_s']=$this->get_upload_path($v['thumb_s']);
			$info[$k]['addtime']=date("Y-m-d H:i:s",$v['addtime']);
			if($uid<0){
				$info[$k]['islike']='0';
				$info[$k]['isattent']='0';

			}else{
				$info[$k]['islike']=(string)round(0,1);;
				$info[$k]['isattent']=(string)round(0,1);;
			}


			$info[$k]['musicinfo']=$info[$k]['userinfo']['user_nicename'];
			$info[$k]['href']=$this->get_upload_path($v['href']);

			$info[$k]['isstep']='0'; //以下字段基本无用
			$info[$k]['isdialect']='0';
			unset($info[$k]['weight']);
			unset($info[$k]['is_urge']);
			unset($info[$k]['urge_nums']);
			unset($info[$k]['urge_money']);
			unset($info[$k]['big_urgenums']);
			unset($info[$k]['status']);
		}


		return $info;
	}

	/*获取附近的视频*/
	public function getNearby($uid,$lng,$lat,$p){
		$pnum=20;
		$start=($p-1)*$pnum;

		$prefix= DI()->config->get('dbs.tables.__default__.prefix');

		$info=DI()->notorm->users_video->queryAll("select *, round(6378.138 * 2 * ASIN(SQRT(POW(SIN(( ".$lat." * PI() / 180 - lat * PI() / 180) / 2),2) + COS(".$lat." * PI() / 180) * COS(lat * PI() / 180) * POW(SIN((".$lng." * PI() / 180 - lng * PI() / 180) / 2),2))) * 1000) AS distance FROM ".$prefix."users_video  where uid !=".$uid." and isdel=0 and status=1 order by distance asc,addtime desc limit ".$start.",".$pnum);

		if(!$info){
			return 1001;
		}


		foreach ($info as $k => $v) {
			$info[$k]['userinfo']=$this->getUserInfo($v['uid']);
			$info[$k]['datetime']=$this->datetime($v['addtime']);
			$info[$k]['thumb']=$this->get_upload_path($v['thumb']);
			$info[$k]['thumb_s']=$this->get_upload_path($v['thumb_s']);
			$info[$k]['addtime']=date("Y-m-d H:i:s",$v['addtime']);
			if($uid<0){
				$info[$k]['islike']='0';
				$info[$k]['isattent']='0';

			}else{
				$info[$k]['islike']=(string)round(0,1);;
				$info[$k]['isattent']=(string)round(0,1);
			}


			$info[$k]['musicinfo']=$info[$k]['userinfo']['user_nicename'];

			$info[$k]['href']=$this->get_upload_path($v['href']);

			$info[$k]['distance']=$this->distanceFormat($v['distance']);

			$info[$k]['isstep']='0'; //以下字段基本无用
			$info[$k]['isdialect']='0';
			unset($info[$k]['weight']);
			unset($info[$k]['is_urge']);
			unset($info[$k]['urge_nums']);
			unset($info[$k]['urge_money']);
			unset($info[$k]['big_urgenums']);
			unset($info[$k]['status']);
			
		}
		
		return $info;
	}

}
