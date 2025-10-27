import { useState, useEffect } from 'react';

interface ProductGalleryProps {
  images: string[];
}

export const ProductGallery = ({ images }: ProductGalleryProps) => {
  const [currentIndex, setCurrentIndex] = useState(0);
  const [thumbnailOffset, setThumbnailOffset] = useState(0);

  // Duplicate images for infinite scroll effect
  const duplicatedImages = [...images, ...images, ...images];

  const nextImage = () => {
    setCurrentIndex((prev) => (prev + 1) % images.length);
  };

  const prevImage = () => {
    setCurrentIndex((prev) => (prev - 1 + images.length) % images.length);
  };

  // Infinite thumbnail scroll animation
  useEffect(() => {
    const thumbnailHeight = 169; // 155px height + 14px gap
    setThumbnailOffset((prev) => {
      const newOffset = prev + thumbnailHeight;
      // Reset when we've scrolled through one full set
      if (newOffset >= thumbnailHeight * images.length) {
        return 0;
      }
      return newOffset;
    });
  }, [currentIndex, images.length]);

  return (
    <div className="w-full max-w-[1341px] mx-auto px-5 relative">
      <div className="flex items-start gap-[14px] md:flex-row flex-col">
        {/* Main Image */}
        <div className="relative w-full md:w-[950px]">
          <img
            src={images[currentIndex]}
            alt={`Product image ${currentIndex + 1}`}
            className="w-full h-[400px] md:h-[493px] object-cover rounded-[30px] md:rounded-[50px]"
          />
          
          {/* Navigation Arrows - Desktop - Aligned to bottom right */}
          <div className="hidden md:flex absolute bottom-8 right-8 items-center gap-[17px] z-10">
            <button
              onClick={prevImage}
              className="flex w-[70px] h-[70px] items-center justify-center rounded-full border-2 border-white bg-white/30 backdrop-blur-sm hover:bg-white/40 transition-all"
              aria-label="Previous image"
            >
              <svg width="24" height="36" viewBox="0 0 24 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fillRule="evenodd" clipRule="evenodd" d="M18.7472 1.50415L16.5702 -0.708374L0.599304 15.5208C0.215011 15.9173 0 16.4478 0 17C0 17.5522 0.215011 18.0827 0.599304 18.4791L16.5702 34.7083L18.7472 32.4937L3.50137 17L18.7472 1.50415Z" fill="white"/>
              </svg>
            </button>
            
            <button
              onClick={nextImage}
              className="flex w-[70px] h-[70px] items-center justify-center rounded-full bg-white hover:bg-gray-100 transition-all"
              aria-label="Next image"
            >
              <svg width="24" height="36" viewBox="0 0 24 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fillRule="evenodd" clipRule="evenodd" d="M4.99983 34.4959L7.17686 36.7084L23.1477 20.4792C23.5321 20.0827 23.7471 19.5522 23.7471 19C23.7471 18.4478 23.5321 17.9173 23.1477 17.5209L7.17686 1.29166L4.99983 3.50626L20.2457 19L4.99983 34.4959Z" fill="#3F3F3F" fillOpacity="0.78"/>
              </svg>
            </button>
          </div>

          {/* Navigation Arrows - Mobile */}
          <div className="flex md:hidden absolute bottom-4 left-1/2 transform -translate-x-1/2 items-center gap-3">
            <button
              onClick={prevImage}
              className="flex w-12 h-12 items-center justify-center rounded-full border-2 border-white bg-white/30 backdrop-blur-sm"
              aria-label="Previous image"
            >
              <svg width="16" height="24" viewBox="0 0 24 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fillRule="evenodd" clipRule="evenodd" d="M18.7472 1.50415L16.5702 -0.708374L0.599304 15.5208C0.215011 15.9173 0 16.4478 0 17C0 17.5522 0.215011 18.0827 0.599304 18.4791L16.5702 34.7083L18.7472 32.4937L3.50137 17L18.7472 1.50415Z" fill="white"/>
              </svg>
            </button>
            
            <button
              onClick={nextImage}
              className="flex w-12 h-12 items-center justify-center rounded-full bg-white"
              aria-label="Next image"
            >
              <svg width="16" height="24" viewBox="0 0 24 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fillRule="evenodd" clipRule="evenodd" d="M4.99983 34.4959L7.17686 36.7084L23.1477 20.4792C23.5321 20.0827 23.7471 19.5522 23.7471 19C23.7471 18.4478 23.5321 17.9173 23.1477 17.5209L7.17686 1.29166L4.99983 3.50626L20.2457 19L4.99983 34.4959Z" fill="#3F3F3F" fillOpacity="0.78"/>
              </svg>
            </button>
          </div>
        </div>

        {/* Thumbnail Images - Desktop Only with Infinite Scroll */}
        <div className="hidden md:block w-[314px] h-[493px] overflow-hidden relative">
          <div 
            className="flex flex-col gap-[14px] transition-transform duration-500 ease-in-out"
            style={{ 
              transform: `translateY(-${thumbnailOffset}px)`,
            }}
          >
            {duplicatedImages.map((image, index) => (
              <img
                key={index}
                src={image}
                alt={`Thumbnail ${(index % images.length) + 1}`}
                className="w-[314px] h-[155px] object-cover rounded-[30px] cursor-pointer hover:opacity-80 transition-opacity flex-shrink-0"
                onClick={() => setCurrentIndex(index % images.length)}
              />
            ))}
          </div>
        </div>
      </div>
    </div>
  );
};
