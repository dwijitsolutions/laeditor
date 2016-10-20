<?php
/**
 * Command for LaraAdmin Installation
 * Help: http://laraadmin.com
 */

namespace Dwij\Laeditor\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Dwij\Laraadmin\Helpers\LAHelper;
use Eloquent;
use DB;


class LAEditor extends Command
{
	/**
	 * The command signature.
	 *
	 * @var string
	 */
	protected $signature = 'la:editor';

	/**
	 * The command description.
	 *
	 * @var string
	 */
	protected $description = 'Install LaraAdmin Editor Package';
	
	protected $from;
	protected $to;

	/**
	 * Generate Whole structure for /admin
	 *
	 * @return mixed
	 */
	public function handle()
	{
		try {
			$this->info('LaraAdmin Code Editor installation started...');
			
			$from = base_path('vendor/dwij/laeditor/src/Installs');
			$to = base_path();
			
			$this->info('from: '.$from." to: ".$to);

			$this->copyFile($from."/resources/views/index.blade.php", $to."/resources/views/la/editor/index.blade.php");
			$this->copyFolder($from."/la-assets/plugins/ace", $to."/public/la-assets/plugins/ace");
			
			$this->info("\nLaraAdmin Code Editor successfully installed.");
			$this->info("Find it on yourdomain.com/".config('laraadmin.adminRoute')."/laeditor !!!\n");

		} catch (Exception $e) {
			$msg = $e->getMessage();
			$this->error("LAEditor::handle exception: ".$e);
			throw new Exception("LAEditor::handle Unable to install : ".$msg, 1);
		}
	}
	
	private function openFile($from) {
		$md = file_get_contents($from);
		return $md;
	}
	
	private function writeFile($from, $to) {
		$md = file_get_contents($from);
		file_put_contents($to, $md);
	}
	
	private function copyFolder($from, $to) {
		// $this->info("copyFolder: ($from, $to)");
		LAHelper::recurse_copy($from, $to);
	}
	
	private function replaceFolder($from, $to) {
		// $this->info("replaceFolder: ($from, $to)");
		if(file_exists($to)) {
			LAHelper::recurse_delete($to);
		}
		LAHelper::recurse_copy($from, $to);
	}
	
	private function copyFile($from, $to) {
		// $this->info("copyFile: ($from, $to)");
		if(!file_exists(dirname($to))) {
			$this->info("mkdir: (".dirname($to).")");
			mkdir(dirname($to));
		}
		copy($from, $to);
	}
	
	private function appendFile($from, $to) {
		// $this->info("appendFile: ($from, $to)");
		
		$md = file_get_contents($from);
		
		file_put_contents($to, $md, FILE_APPEND);
	}
	
	// TODO:Method not working properly
	private function fileContains($filePath, $text) {
		$fileData = file_get_contents($filePath);
		if (strpos($fileData, $text) === false ) {
			return true;
		} else {
			return false;
		}
	}
}
