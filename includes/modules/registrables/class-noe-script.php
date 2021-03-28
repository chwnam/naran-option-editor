<?php
/**
 * Script
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NOE_Script' ) ) {
	class NOE_Script implements NOE_Registrable {
		public string $handle;
		public string $src;
		public array $deps;
		public ?string $ver;
		public bool $in_footer;

		public function __construct(
			string $handle,
			string $relpath,
			array $deps = [],
			string $ver = NOE_VERSION,
			bool $in_footer = false
		) {
			$this->handle    = $handle;
			$this->src       = plugins_url( 'assets/js/' . $relpath, NOE_MAIN );
			$this->deps      = $deps;
			$this->ver       = $ver;
			$this->in_footer = $in_footer;
		}

		public function register() {
			if ( $this->handle && $this->src ) {
				wp_register_script(
					$this->handle,
					$this->src,
					$this->deps,
					$this->ver,
					$this->in_footer
				);
			}
		}
	}
}
