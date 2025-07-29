<?php

namespace E03Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("", name="e03_colors_no_slash")
     * @Route("/", name="e03_colors")
     */
    public function indexAction()
    {
        $numShades = $this->getParameter('e03.number_of_colors');
        $colors = ['black', 'red', 'blue', 'green'];

        $table = [];

        for ($i = 0; $i < $numShades; $i++) {
            $row = [];
            foreach ($colors as $color) {
                $intensity = intval(255 * ($i + 1) / $numShades);
                switch ($color) {
                    case 'black': $rgb = sprintf('#%02x%02x%02x', $intensity, $intensity, $intensity); break;
                    case 'red':   $rgb = sprintf('#%02x0000', $intensity); break;
                    case 'green': $rgb = sprintf('#00%02x00', $intensity); break;
                    case 'blue':  $rgb = sprintf('#0000%02x', $intensity); break;
                    default:      $rgb = '#ffffff'; break;
                }
                $row[] = $rgb;
            }
            $table[] = $row;
        }

        return $this->render('E03Bundle:Default:index.html.twig', [
            'colors' => $colors,
            'shades' => $table
        ]);
    }
}

