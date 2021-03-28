<?php
/**
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NOE_Style' ) ) {
	class NOE_Style implements NOE_Registrable {
		public string $handle;
		public string $src;
		public array $deps;
		public ?string $ver;
		public string $media;

		public function __construct(
			string $handle,
			string $relpath,
			array $deps = [],
			string $ver = NOE_VERSION,
			string $media = 'all'
		) {
			$this->handle = $handle;
			$this->src    = plugins_url( 'assets/css/' . $relpath, NOE_MAIN );
			$this->deps   = $deps;
			$this->ver    = $ver;
			$this->media  = $media;
		}

		public function register() {
			if ( $this->handle && $this->src ) {
				wp_register_style( $this->handle, $this->src, $this->deps, $this->ver, $this->media );
			}
		}
	}
}
