<script>
	<?php include $_SERVER['DOCUMENT_ROOT'] . '/localforage.min.js' ?>	
</script>
<script>
	// <link rel="katyusha" href="" />
	const cssEksternal = document.querySelectorAll('link[rel=katyusha]')
	if (cssEksternal.length > 0) {
		cssEksternal.forEach(css => {
			localforage.getItem(css.href).then(x => {
				if (x){
					css.outerHTML = `<style>${x}</style>`
				} else {
					fetch(css.href).then(x => x.text()).then(hasilnya => {
						css.outerHTML = `<style>${hasilnya}</style>`
						localforage.setItem(css.href, hasilnya)
					})
				}
			})
		})
	}

	
</script>