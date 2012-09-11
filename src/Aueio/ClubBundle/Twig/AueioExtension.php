<?php
// src/Aueio/ClubBundle/Twig/AueioExtension.php
namespace Aueio\ClubBundle\Twig;

use Aueio\ClubBundle\Entity\Player;
use Twig_Extension;
use Twig_Filter_Method;

class AueioExtension extends Twig_Extension
{
	public function getFilters()
	{
		return array(
				'dateFormatFR' => new Twig_Filter_Method($this, 'dateFormatFRFilter'),
				'displayName' => new Twig_Filter_Method($this, 'displayName'),
		);
	}
	public function displayName($player)
	{
		if($player instanceof Player){
			return $player->getFirstname() . ' ' . substr($player->getLastname(), 0, 1);
		}else{
			return $player['firstname'] . ' ' . substr($player['lastname'], 0, 1);
		}
	}
	public function dateFormatFRFilter($date, $format = null)
	{
	
    	$dateFormatter = \IntlDateFormatter::create(
    			\Locale::getDefault(),
    			\IntlDateFormatter::NONE,
    			\IntlDateFormatter::NONE,
    			date_default_timezone_get(),
    			\IntlDateFormatter::GREGORIAN,
    			null
    	);
    	if(is_long($date)){
    		$date = new \DateTime("@{$date}");
    	}
    	if(! $date instanceof \DateTime){
    		$date = new \DateTime($date);
    	}
    	if(!$format){
	    	$dateFormatter->setPattern('EEEE');
	    	$day = "<div class=\"day\">{$dateFormatter->format($date)}</div>";
	    	$dateFormatter->setPattern('dd');
	    	$nb = "<div class=\"nb\">{$dateFormatter->format($date)}</div>";
	    	$dateFormatter->setPattern('MMMM');
	    	$month = "<div class=\"month\">{$dateFormatter->format($date)}</div>";
	    	$dateFormatter->setPattern('yyyy');
	    	$year = "<div class=\"year\">{$dateFormatter->format($date)}</div>";
	    	return $day . $nb . $month . $year;
    	}else{
    		$dateFormatter->setPattern($format);
    		return $dateFormatter->format($date);
    	}
    }

	public function getName()
	{
		return 'aueio_extension';
	}
}
