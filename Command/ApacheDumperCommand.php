<?php

/*
 * This file is part of the CodespaWebBundle package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RC\PHPCR\FileAttachInlineBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


/**
 * @author Rafael Calleja <rafa.calleja@d-noise.net>
 */
class ApacheDumperCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('rc:apache:dump')
            ->setDescription('Dump Apache rewrite rules')
            /*->setDefinition( array(
                new InputArgument('file', InputArgument::REQUIRED, 'The fixture file'),
                new InputArgument('phpcrnode', InputArgument::REQUIRED, 'The node deleted'),
            	new InputArgument('loader', InputArgument::REQUIRED, 'The loader class'),
                //new InputOption('super-admin', null, InputOption::VALUE_NONE, 'Set the user as super admin'),
            ))*/
            ->setHelp(<<<EOT
            	<info>
________$$$\$______________________________
_______$$$$$$\$_________________________$$$
________$$$$$$\$_____________________$$$$
_________$$$$$$\$____$\$_____$\$____$$$$$
__________$$$$$$\$_$$$$\$_$$$$\$__$$$$$$$
___________$$$$$$\$_$$$$$$\$_$$$$$$\$_$$$$$$\$_
____________$$$$$\$_$$$$$$\$_$$$$$$$$$$$$\$_
_________$$$$$\$_$$$$$$\$_$$$$$$\$_$$$$$\$_
_$$$$\$____$$$$$\$_$$$$$$\$_$$$$$$\$_$$$$$\$_
$$$$$$$$$$$$$$$\$_$$$$$$\$_$$$$$$\$_$$$$\$_
$$$$$$$$$$$$$$$$$\$_$$$$$$\$_$$$$$$\$_$$$$\$_
___$$$$$$$$$$$$$$\$_$$$$$$\$_$$$$$\$_$$$$$\$_
______$$$$$$$$$$$$\$_$$$$\$__$\$_$$$$$\$_$\$_
_______$$$$$$$$$$$\$___$$$\$_____$$$$\$_
_________$$$$$$$$$$$$$$$$$$$$$$$$$$$$\$_
__________$$$$$$$$$$$$$$$$$$$$$$$$$$\$_
____________$$$$$$$$$$$$$$$$$$$$$$$\$_
_______________$$$$$$$$$$$$$$$$$$$\$_
            		</info>
EOT
            );
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output){
        $extensions = $this->getContainer()->getParameter('rcphpcr_file_attach_inline.extensions');
        $extensions = array_merge($extensions, array_map('strtoupper', $extensions));
        
        $controller = str_replace("\\", '\\\\', $this->getContainer()->getParameter('rc_phpcr.controller.class') );
        $action = strstr($this->getContainer()->getParameter('rcphpcr_file_attach_inline.controller_action'), ':');
        
        $rules[] = str_replace('__extensions__', implode('|', $extensions), 'RewriteCond %{REQUEST_URI} ^/(.*\.(__extensions__))$');
        $method = str_replace('__action__', $action, "RewriteRule .* app.php [QSA,L,E=_ROUTING__route:rcphpcr_file_attach_inline_homepage,E=_ROUTING_file:%1,E=_ROUTING_DEFAULTS__controller:__controller__\:\__action__]");
        $rules[] = str_replace('__controller__', $controller, $method);
        
        
	    $output->writeln( implode("\n", $rules), OutputInterface::OUTPUT_RAW);
    }

    /**
     * @see Command
     */
    protected function interact(InputInterface $input, OutputInterface $output){
        

    }
}