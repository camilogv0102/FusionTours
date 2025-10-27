import React, { useState, useRef, useEffect } from 'react';

const ClientGallery = () => {
  const [currentImageIndex, setCurrentImageIndex] = useState(0);
  const scrollRef = useRef<HTMLDivElement>(null);
  const autoScrollRef = useRef<NodeJS.Timeout | null>(null);

  const leftImages = [
    { src: "/images/clients/client-1.jpg", alt: "Cliente feliz 1" },
    { src: "/images/clients/client-2.jpg", alt: "Cliente feliz 2" },
    { src: "/images/clients/client-3.jpg", alt: "Cliente feliz 3" },
    { src: "/images/clients/client-4.jpg", alt: "Cliente feliz 4" },
    { src: "/images/clients/client-5.jpg", alt: "Cliente feliz 5" }
  ];

  const rightImages = [
    { src: "/images/clients/client-6.jpg", alt: "Cliente feliz 6" },
    { src: "/images/clients/client-7.jpg", alt: "Cliente feliz 7" },
    { src: "/images/clients/client-8.jpg", alt: "Cliente feliz 8" },
    { src: "/images/clients/client-9.jpg", alt: "Cliente feliz 9" },
    { src: "/images/clients/client-10.jpg", alt: "Cliente feliz 10" }
  ];

  const allImages = [...leftImages, ...rightImages];

  // Auto-scroll for mobile carousel - faster animation
  useEffect(() => {
    const startAutoScroll = () => {
      autoScrollRef.current = setInterval(() => {
        setCurrentImageIndex((prev) => (prev + 1) % allImages.length);
      }, 2500); // Reduced from 4000ms to 2500ms for faster animation
    };

    startAutoScroll();

    return () => {
      if (autoScrollRef.current) {
        clearInterval(autoScrollRef.current);
      }
    };
  }, [allImages.length]);

  // Scroll to current image on mobile
  useEffect(() => {
    if (scrollRef.current) {
      const scrollLeft = currentImageIndex * (scrollRef.current.offsetWidth);
      scrollRef.current.scrollTo({
        left: scrollLeft,
        behavior: 'smooth'
      });
    }
  }, [currentImageIndex]);

  return (
    <section className="box-border w-full relative m-0 px-20 py-12 max-md:px-10 max-sm:px-5 overflow-hidden z-0">
      <div className="max-w-7xl mx-auto">
        {/* Desktop Version - Grid de 3 columnas */}
        <div className="hidden md:grid grid-cols-[1fr_auto_1fr] gap-8 items-center max-md:grid-cols-1">
          
          {/* Columna izquierda - 5 imágenes escalonadas */}
          <div className="flex flex-col relative">
            <img
              src={leftImages[0].src}
              alt={leftImages[0].alt}
              className="box-border rounded-lg object-cover shadow-lg hover:scale-105 transition-all duration-200"
              style={{ width: '240px', height: '180px', marginLeft: '60px', marginBottom: '10px' }}
            />
            <img
              src={leftImages[1].src}
              alt={leftImages[1].alt}
              className="box-border rounded-lg object-cover shadow-lg hover:scale-105 transition-all duration-200"
              style={{ width: '280px', height: '200px', marginTop: '-30px', marginLeft: '15px' }}
            />
            <img
              src={leftImages[2].src}
              alt={leftImages[2].alt}
              className="box-border rounded-lg object-cover shadow-lg hover:scale-105 transition-all duration-200"
              style={{ width: '260px', height: '190px', marginTop: '-20px', marginLeft: '80px' }}
            />
            <img
              src={leftImages[3].src}
              alt={leftImages[3].alt}
              className="box-border rounded-lg object-cover shadow-lg hover:scale-105 transition-all duration-200"
              style={{ width: '300px', height: '220px', marginTop: '-25px', marginLeft: '5px' }}
            />
            <img
              src={leftImages[4].src}
              alt={leftImages[4].alt}
              className="box-border rounded-lg object-cover shadow-lg hover:scale-105 transition-all duration-200"
              style={{ width: '240px', height: '180px', marginTop: '-15px', marginLeft: '70px' }}
            />
          </div>
          
          {/* Columna central - Texto */}
          <div className="flex flex-col items-center gap-6 px-8 py-8">
            <div className="bg-white/95 backdrop-blur-sm rounded-xl p-6 text-center max-w-sm shadow-lg">
              <h2 className="box-border text-black text-center text-[32px] font-bold leading-tight m-0 p-0 mb-4">
                Nuestros Clientes
              </h2>
              <p className="box-border text-[#6F6F6F] text-center text-sm font-normal leading-relaxed m-0 p-0 mb-6">
                Cada experiencia compartida nos motiva a seguir ofreciendo tours inolvidables, atención personalizada y momentos únicos en la Riviera Maya.
              </p>
              <button className="box-border flex justify-center items-center gap-3 bg-[#0070C0] m-0 mx-auto px-6 py-2.5 rounded-full hover:bg-[#005a9f] transition-colors">
                <span className="box-border text-white text-center text-sm font-bold uppercase m-0 p-0">
                  Ver más
                </span>
              </button>
            </div>
          </div>

          {/* Columna derecha - 5 imágenes escalonadas */}
          <div className="flex flex-col relative">
            <img
              src={rightImages[0].src}
              alt={rightImages[0].alt}
              className="box-border rounded-lg object-cover shadow-lg hover:scale-105 transition-all duration-200"
              style={{ width: '280px', height: '200px', marginTop: '30px', marginLeft: '10px' }}
            />
            <img
              src={rightImages[1].src}
              alt={rightImages[1].alt}
              className="box-border rounded-lg object-cover shadow-lg hover:scale-105 transition-all duration-200"
              style={{ width: '240px', height: '180px', marginTop: '-20px', marginLeft: '75px' }}
            />
            <img
              src={rightImages[2].src}
              alt={rightImages[2].alt}
              className="box-border rounded-lg object-cover shadow-lg hover:scale-105 transition-all duration-200"
              style={{ width: '300px', height: '220px', marginTop: '-25px', marginLeft: '0px' }}
            />
            <img
              src={rightImages[3].src}
              alt={rightImages[3].alt}
              className="box-border rounded-lg object-cover shadow-lg hover:scale-105 transition-all duration-200"
              style={{ width: '260px', height: '190px', marginTop: '-30px', marginLeft: '65px' }}
            />
            <img
              src={rightImages[4].src}
              alt={rightImages[4].alt}
              className="box-border rounded-lg object-cover shadow-lg hover:scale-105 transition-all duration-200"
              style={{ width: '280px', height: '200px', marginTop: '-15px', marginLeft: '20px' }}
            />
          </div>
        </div>

        {/* Mobile Version - Polaroid Card Style */}
        <div className="md:hidden flex flex-col items-center gap-6">
          {/* Título arriba */}
          <h2 className="text-black text-center text-[28px] font-bold leading-tight max-sm:text-[24px]">
            Qué dicen nuestros clientes
          </h2>

          {/* Polaroid Card Container */}
          <div className="bg-white rounded-2xl shadow-2xl p-6 max-w-md w-full">
            {/* Image Carousel */}
            <div className="relative mb-6">
              <div 
                ref={scrollRef}
                className="overflow-hidden rounded-lg"
              >
                <div 
                  className="flex transition-transform duration-500 ease-out"
                  style={{ transform: `translateX(-${currentImageIndex * 100}%)` }}
                >
                  {allImages.map((image, index) => (
                    <img
                      key={index}
                      src={image.src}
                      alt={image.alt}
                      className="w-full h-[280px] object-cover flex-shrink-0"
                    />
                  ))}
                </div>
              </div>

              {/* Subtle scroll indicator - animated bouncing arrows */}
              <div className="absolute bottom-3 right-3 flex gap-1 animate-pulse">
                <div className="w-1.5 h-1.5 rounded-full bg-white/80 animate-bounce" style={{ animationDelay: '0ms' }}></div>
                <div className="w-1.5 h-1.5 rounded-full bg-white/80 animate-bounce" style={{ animationDelay: '150ms' }}></div>
                <div className="w-1.5 h-1.5 rounded-full bg-white/80 animate-bounce" style={{ animationDelay: '300ms' }}></div>
              </div>

              {/* Image counter dots */}
              <div className="flex justify-center gap-2 mt-4">
                {allImages.map((_, index) => (
                  <button
                    key={index}
                    onClick={() => setCurrentImageIndex(index)}
                    className={`w-2 h-2 rounded-full transition-all duration-300 ${
                      index === currentImageIndex ? 'bg-[#0070C0] w-6' : 'bg-gray-300'
                    }`}
                    aria-label={`Ver imagen ${index + 1}`}
                  />
                ))}
              </div>
            </div>

            {/* Text Content */}
            <div className="text-center">
              <p className="text-[#6F6F6F] text-sm leading-relaxed mb-4">
                Cada experiencia compartida nos motiva a seguir ofreciendo tours inolvidables, atención personalizada y momentos únicos en la Riviera Maya.
              </p>
              <button className="bg-[#0070C0] text-white px-6 py-2.5 rounded-full text-sm font-bold uppercase hover:bg-[#005a9f] transition-colors">
                Ver más
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
};

export default ClientGallery;
