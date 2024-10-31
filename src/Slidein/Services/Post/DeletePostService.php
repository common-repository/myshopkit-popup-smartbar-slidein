<?php


namespace MyShopKitPopupSmartBarSlideIn\Slidein\Services\Post;


use Exception;

use MyShopKitPopupSmartBarSlideIn\Illuminate\Message\MessageFactory;
use MyShopKitPopupSmartBarSlideIn\Shared\Post\IDeleteUpdateService;
use MyShopKitPopupSmartBarSlideIn\Shared\Post\TraitIsPostAuthor;
use MyShopKitPopupSmartBarSlideIn\Shared\Post\TraitPostType;
use WP_Post;

class DeletePostService implements IDeleteUpdateService {
	use TraitIsPostAuthor;
	use TraitPostType;

	private $postID;

	public function setID( $id ): self {
		$this->postID = $id;

		return $this;
	}


	public function delete(): array {
		try {
			$this->isPostAuthor( $this->postID );
			$this->isPostType( $this->postID, $this->getPostType(plugin_dir_path( __FILE__) . '../../Configs/'));

			$oPost = wp_delete_post( $this->postID, true );

			if ( $oPost instanceof WP_Post ) {
				return MessageFactory::factory()->success( esc_html__( 'Congrats, the Slidein has been deleted.',
					'myshopkit-popup-smartbar-slidein' ), [
					'id' => (string) $oPost->ID
				] );
			}

			return MessageFactory::factory()->error( esc_html__( 'Sorry, We could not delete this Slidein.',
				'myshopkit-popup-smartbar-slidein' ),
				400 );
		}
		catch ( Exception $oException ) {
			return MessageFactory::factory()->error(
				$oException->getMessage(),
				$oException->getCode()
			);
		}

	}
}
