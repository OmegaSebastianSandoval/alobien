<div data-aos="" class="row mt-4 slider-<?php echo $columna->contenido_id; ?>">
  <?php if ($columna->contenido_titulo_ver == 1): ?>
    <h2><?php echo htmlspecialchars($columna->contenido_titulo); ?></h2>
  <?php endif; ?>
  
  <?php echo $columna->contenido_descripcion; ?>
  
  <div id="slider_<?php echo $columna->contenido_id; ?>"
       class="slider_<?php echo $columna->contenido_id; ?> col-sm-12 sliderCont w-100">
    <?php foreach ($slidercontent as $slider): ?>
      <?php 
      // Adaptar a la nueva estructura jerárquica
      $slider = $slider["detalle"];
      $hasLink = !empty($slider->contenido_enlace);
      $hasImage = !empty($slider->contenido_imagen);
      $hasDescription = !empty($slider->contenido_descripcion);
      $hasIntroduction = !empty($slider->contenido_introduccion);
      $showTitle = ($slider->contenido_titulo_ver == 1);
      $linkTarget = ($slider->contenido_enlace_abrir == '1') ? '_blank' : '_self';
      ?>

      <div class="itemSlider itemSlider_<?php echo $columna->contenido_id; ?>">
        
        <?php if ($hasLink): ?>
          <a href="<?php echo htmlspecialchars($slider->contenido_enlace); ?>" 
             target="<?php echo $linkTarget; ?>">
        <?php endif; ?>
        
        <!-- Imagen del slider -->
        <?php if ($hasImage): ?>
          <img class="img-slider" 
               src="/images/<?php echo htmlspecialchars($slider->contenido_imagen); ?>"
               alt="<?php echo htmlspecialchars($slider->contenido_titulo); ?>">
        <?php else: ?>
          <img class="img-slider" 
               src="/assets/pic7.jpg" 
               alt="<?php echo htmlspecialchars($slider->contenido_titulo); ?>">
        <?php endif; ?>
        
        <!-- Contenido del slider -->
        <div class="content-slider content-slider_<?php echo $columna->contenido_id; ?>">
          
          <?php if ($hasDescription): ?>
            <div class="descripcion-slider">
              <?php echo $slider->contenido_descripcion; ?>
            </div>
          <?php endif; ?>

          <?php if ($showTitle): ?>
            <h3><?php echo htmlspecialchars($slider->contenido_titulo); ?></h3>
          <?php endif; ?>

          <?php if ($hasIntroduction): ?>
            <div class="introduccion-slider">
              <?php echo $slider->contenido_introduccion; ?>
            </div>
          <?php endif; ?>
          
        </div>

        <?php if ($hasLink): ?>
          </a>
        <?php endif; ?>

      </div>
    <?php endforeach; ?>
  </div>
</div>

<script>
$(document).ready(function() {
  
  // Configuración del slider
  const sliderId = '#slider_<?php echo $columna->contenido_id; ?>';
  const isSpecialSlider = (<?php echo $columna->contenido_id; ?> === 23);
  
  // Configuración básica del slider
  const sliderConfig = {
    infinity: false,
    slidesToShow: isSpecialSlider ? 2 : 7,
    slidesToScroll: 1,
    autoplay: false,
    autoplaySpeed: 2000,
    dots: false,
    arrows: true,
    
    responsive: [
      {
        breakpoint: 1200,
        settings: {
          infinity: false,
          slidesToShow: isSpecialSlider ? 1 : 3,
          slidesToScroll: 1,
          dots: false,
          arrows: true
        }
      },
      {
        breakpoint: 900,
        settings: {
          slidesToShow: isSpecialSlider ? 1 : 2,
          slidesToScroll: 1,
          dots: true,
          arrows: false
        }
      },
      {
        breakpoint: 770,
        settings: {
          slidesToShow: isSpecialSlider ? 1 : 2,
          slidesToScroll: 1,
          dots: true,
          arrows: false
        }
      }
    ]
  };
  
  // Inicializar slider
  $(sliderId).slick(sliderConfig);
  
  // Funcionalidad específica para slider_18 en dispositivos móviles
  if (window.innerWidth <= 765) {
    const slider18 = $('.slider_18');
    
    // Agregar clase inicial a slides visibles
    slider18.find('div.slick-active .content-slider').addClass('content-slider_18');
    
    // Manejar cambios de slide
    slider18.on('afterChange', function(event, slick, currentSlide) {
      // Remover clase de todos los slides
      slider18.find('div .content-slider').removeClass('content-slider_18');
      
      // Añadir clase solo a slides visibles
      slider18.find('div.slick-active .content-slider').addClass('content-slider_18');
    });
  }
  
});
</script>