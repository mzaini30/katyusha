<script>
	<?php include $_SERVER['DOCUMENT_ROOT'] . '/localforage.min.js' ?>	
</script>
<script>
	let versi = 'base'
	if (typeof versiKatyusha != 'undefined') {
		versi = versiKatyusha
	}

	let ringkasan = {
		versi: '',
		link: []
	}
	ringkasan.versi = versi

	window.addEventListener('DOMContentLoaded', () => {
		const cssEksternal = document.querySelectorAll('link[rel=katyusha]')
		if (cssEksternal.length > 0) {
			cssEksternal.forEach(css => {
				localforage.getItem(`${css.href}-${versi}`).then(x => {
					if (x){
						css.outerHTML = `<style>${x}</style>`
					} else {
						fetch(css.href).then(x => x.text()).then(hasilnya => {
							localforage.setItem(`${css.href}-${versi}`, hasilnya).catch(x => console.log(x))
							css.outerHTML = `<style>${hasilnya}</style>`
						}).catch(x => console.log(x))
					}
				}).catch(x => console.log(x))
			})
		}

		const gambar = document.querySelectorAll('img[data-src]')
		if (gambar.length > 0){
			gambar.forEach(gambarnya => {
				localforage.getItem(`${gambarnya.dataset.src}-${versi}`).then(x => {
					if (x){
						gambarnya.src = x
					} else {
						fetch(`https://scrappy-php.herokuapp.com/?url=${encodeURIComponent(gambarnya.dataset.src)}`).then(x => x.blob()).then(gambarFetch => {
							const reader = new FileReader
							reader.readAsDataURL(gambarFetch)
							reader.addEventListener('load', x => {
								const hasil = x.target.result
								localforage.setItem(`${gambarnya.dataset.src}-${versi}`, hasil).catch(x => console.log(x))
								gambarnya.src = hasil
							})
						}).catch(x => console.log(x))
					}
				}).catch(x => console.log(x))
			})
		}

		function buat(js, isinya){
			js.removeAttribute('src')
			if (js.type == 'katyushaModule') {
				js.type = 'module'
			} else {
				js.removeAttribute('type')
			}

			const semuaAttribute = [...js.attributes]
			
			const elemenBaru = document.createElement('script')
			for (let z of semuaAttribute){
				elemenBaru.setAttribute(z.name, z.value)
			}
			elemenBaru.innerHTML = isinya
			js.parentNode.replaceChild(elemenBaru, js)
		}

		const semuaJs = document.querySelectorAll('script[type=katyusha], script[type=katyushaModule]')
		async function olahJs(){
			if (semuaJs.length > 0){
				for (let js of semuaJs){
				// semuaJs.forEach(js => {
					if (js.hasAttribute('src')){
						ringkasan.link = [...ringkasan.link, `${js.src}-${versi}`]
						
						if (localStorage.getItem(`${js.src}-${versi}`)){
							const isinya = localStorage.getItem(`${js.src}-${versi}`)
							buat(js, isinya)
						} else {
							// let data = await fetch(el.src).then(x => x.text()).catch(x => console.log(x))

							let isinya = await fetch(js.src).then(x => x.text()).catch(x => console.log(x))
							if (isinya){
								localStorage.setItem(`${js.src}-${versi}`, isinya)
								buat(js, isinya)
							}

							// fetch(js.src).then(ambil => ambil.text()).then(isinya => {
							// 	localStorage.setItem(`${js.src}-${versi}`, isinya)
							// 	buat(js, isinya)
							// })
						}
					} else {
						buat(js, js.innerHTML)
					}

				// })
				}

				// bersih-bersih localStorage
				if (localStorage.scriptKatyusha){
					let scriptKatyusha = JSON.parse(localStorage.scriptKatyusha)
					if (scriptKatyusha.versi != ringkasan.versi){
						for (let x of scriptKatyusha.link){
							localStorage.removeItem(x)
						}
					}
				}
				localStorage.scriptKatyusha = JSON.stringify(ringkasan)
			}
		}
		olahJs()
	})
</script>