<?php
function insert_pages()
{
	$categories = array(
		'MAC' => 3,
		'MAE' => 2,
		'MAT' => 11,
		'MAP' => 10
	);

	$deps = array('MAC', 'MAE', 'MAT', 'MAP');
	foreach ($deps as $dep) {
		$pessoas = busca($dep);
		foreach ($pessoas as $pessoa) {		
			$args = array(
				'post_type' => 'page',
				'pagename'  => $pessoa->info->codlog
			);

			$data = array();
			$the_posts = get_posts( $args );
			if ( empty($the_posts) ) {
				$data['post_title']    = 'Docentes';
				$data['post_type']     = 'page';
				$data['post_status']   = 'publish';
				$data['post_name']     = $pessoa->info->codlog;
				$data['post_category'] = array($categories[$pessoa->nomabvset]);

				$width = 195;
				$height = 265;
				$angle = (atan($height/$width) * 180) / pi();
				$photo_url = "https://www.ime.usp.br/fobos-client/obterfoto.php?cid=" . $pessoa->info->codpes;
				
				$html = '<div style="align-items: flex-start; display: flex;">'
				  . '<div style="background: linear-gradient(' . (180 - $angle) . 'deg, #ccc 50%, #aaa 50%); '
						. 'padding: 0.25em; margin: 20px 2em 0 1em;">'
					. '<img src="' . $photo_url . '" style="border-radius: 1px; width: ' . $width . 'px; height: ' . $height . 'px;">'
				  . '</div>'
				  . '<div style="font-size: 1em;">'
					. '<div>'
					  . '<span style="font-size: 1.5em;"><b>' . $pessoa->nompes . '</b></span>'
					  . '<div>' . $pessoa->nomabvfnc . '</div>'
					  . '<div>' . $pessoa->nomset . '</div>'
					. '</div>'
					. '<br>'
					. '<div>'
					  . '<div><b>Endereço:</b> Rua do Matão, 1010</div>'
					  . '<div>CEP 05508-090 - São Paulo - SP - Brasil</div>'
					  . '<div><b>Sala:</b> '. $pessoa->info->numero . '</div>'
					  . '<div><b>Telefone:</b> '. $pessoa->info->ramal . '</div>'
					. '</div>'
					. '<br>'
					. '<div>'
					  . '<div><b>E-mail:</b> '
						. '<a href="mailto:' . $pessoa->codema . '">' . $pessoa->codema . '</a>'
					  . '</div>'
					  . '<div><b>Página pessoal:</b> '
						. (
						  $pessoa->info->exibirPaginaPessoal ?
						  '<a href=https://www.ime.usp.br/~' . $pessoa->info->codlog . '>https://www.ime.usp.br/~' . $pessoa->info->codlog . '</a>'
						  :
						  '<a href=https://www.ime.usp.br/' . $pessoa->info->codlog . '>https://www.ime.usp.br/' . $pessoa->info->codlog . '</a>'
						)
					  . '</div>'
					. '</div>'
				  . '</div>'
				. '</div>'
				. '<p>' . $pessoa->curriculo->pt . '</p>'
				. '<br>'
				. (
					$pessoa->info->exibirLattes ?
					'<div><a href="' . $pessoa->linkLattes . '">Curriculum Lattes</a></div>'
					:
					''
				  )
				  . '<div>'
					. '<a href="https://orcid.org/' . $pessoa->info->orcidId . '">'
						. 'ORCID - Open Researcher and Contributor Identifier'
					. '</a>'
				  . '</div>'
				  . (
					$pessoa->info->exibirPublicacoes ?
					'<div>'
					  . '<a href="http://bdpi.usp.br/result.php?codpes=' . $pessoa->codpes
					  . '&search%5B%5D=+base.keyword:%22Produ%C3%A7%C3%A3o%20cient%C3%ADfica%22&locale=pt_BR">'
						. 'Publicações na Biblioteca do IME-USP'
					  . '</a>'
					. '</div>'
					:
					''
				  );

				$data['post_content'] = $html;
				wp_insert_post( $data );
			}
		}
	}
}

function delete_pages()
{
	$deps = array('MAC', 'MAE', 'MAT', 'MAP');
	foreach ($deps as $dep) {
		$pessoas = busca($dep);
		foreach ($pessoas as $pessoa) {		
			$args = array(
				'post_type' => 'page',
				'pagename'  => $pessoa->info->codlog
			);

			$the_posts = get_posts( $args );
			if ( !empty($the_posts) ) {
				$a_post = $the_posts[0];
				wp_delete_post( $a_post->ID, true );
			}
		}
	}
}
?>
