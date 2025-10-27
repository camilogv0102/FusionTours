import { useState } from 'react';
import { PricingOption } from '@/types/product';
import { useNavigate } from 'react-router-dom';
import { MessageCircle } from 'lucide-react';
import { useCurrency } from '@/contexts/CurrencyContext';
import { QuoteDialog } from '@/components/QuoteDialog';

interface ProductSidebarProps {
  images: string[];
  pricing?: PricingOption[];
  requiresQuote: boolean;
  whatsappNumber?: string;
  whatsappMessage?: string;
}

export const ProductSidebar = ({ 
  images, 
  pricing, 
  requiresQuote, 
  whatsappNumber,
  whatsappMessage 
}: ProductSidebarProps) => {
  const navigate = useNavigate();
  const { formatPrice } = useCurrency();
  const [isQuoteDialogOpen, setIsQuoteDialogOpen] = useState(false);

  const handleQuoteClick = () => {
    setIsQuoteDialogOpen(true);
  };

  const handleWhatsAppClick = () => {
    if (whatsappNumber) {
      const message = whatsappMessage || 'Hola, me interesa este tour. ¿Podrían darme más información?';
      const url = `https://wa.me/${whatsappNumber}?text=${encodeURIComponent(message)}`;
      window.open(url, '_blank');
    }
  };

  return (
    <div className="w-full md:w-[314px] flex flex-col items-center gap-[42px] md:sticky md:top-[141px] md:self-start md:max-h-[calc(100vh-141px-20px)] md:overflow-y-auto">
      {/* Thumbnail Images - Mobile */}
      <div className="flex md:hidden gap-[14px] overflow-x-auto w-full pb-2">
        {images.slice(0, 3).map((image, index) => (
          <img
            key={index}
            src={image}
            alt={`Thumbnail ${index + 1}`}
            className="min-w-[200px] h-[150px] object-cover rounded-[30px]"
          />
        ))}
      </div>

      {/* Pricing or WhatsApp Section */}
      <div className="w-full max-w-[290px] flex flex-col items-center gap-5">
        {!requiresQuote && pricing ? (
          <>
            {/* Pricing Tables */}
            {pricing.map((option, index) => (
              <div key={index} className="w-full flex flex-col items-center gap-5">
                <p className="text-[#696969] text-left md:text-center font-['Neue_Montreal'] text-sm md:text-base font-bold leading-[26px] uppercase">
                  {option.location}
                </p>
                
                <div className="flex justify-around items-end w-full">
                  {/* Adults */}
                  <div className="flex flex-col items-center gap-[10px]">
                    <div className="flex items-center gap-2">
                      <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clipPath="url(#clip0_adults)">
                          <path d="M12.8928 10.2275C10.0048 10.1828 7.79864 7.86115 7.7791 5.11375C7.82681 2.25148 10.1663 0.0200715 12.8928 0C15.7834 0.109306 17.986 2.32186 18.0066 5.11375C17.9498 7.97828 15.6222 10.2081 12.8928 10.2275ZM17.0274 11.2883C20.6622 11.3308 22.5053 14.5192 22.5221 17.7076V25.5687H18.9588L18.9588 18.6598C18.872 18.1821 18.5485 18.0202 18.2521 18.067C17.9747 18.1109 17.721 18.3376 17.7075 18.6598V25.5687H7.80667V18.6598C7.73088 18.2052 7.44172 18.0304 7.1604 18.0481C6.85916 18.067 6.56693 18.3066 6.55542 18.6598V25.5687H3.04654V17.7076C3.02566 14.3096 5.17516 11.3135 8.56804 11.2883H17.0274Z" fill="#0070C0"/>
                        </g>
                        <defs>
                          <clipPath id="clip0_adults">
                            <rect width="25.5687" height="25.5687" fill="white"/>
                          </clipPath>
                        </defs>
                      </svg>
                      <span className="text-[#696969] text-center font-['Neue_Montreal'] text-sm md:text-base font-normal leading-[26px] uppercase">
                        ADULTOS:
                      </span>
                    </div>
                    <p className="text-black text-center font-['Neue_Montreal'] text-sm md:text-base font-medium leading-[26px] uppercase">
                      {formatPrice(option.adults)}
                    </p>
                  </div>

                  {/* Children */}
                  <div className="flex flex-col items-center gap-[10px]">
                    <div className="flex items-center gap-2">
                      <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12.7844 10.6568C14.0478 10.6568 15.0716 9.63302 15.0716 8.36966C15.0716 7.1063 14.0478 6.08252 12.7844 6.08252C11.521 6.08252 10.4973 7.1063 10.4973 8.36966C10.4973 9.63302 11.521 10.6568 12.7844 10.6568Z" fill="#0070C0"/>
                        <path d="M18.3587 13.5154C18.3587 11.4669 16.6958 9.804 14.6473 9.804H10.9216C8.87307 9.804 7.21021 11.4669 7.21021 13.5154V16.374H9.54878V25.5687H12.7844H16.0201V16.374H18.3587V13.5154Z" fill="#0070C0"/>
                        <path d="M12.7844 3.43609C13.5754 3.43609 14.2158 2.79571 14.2158 2.00471C14.2158 1.21372 13.5754 0.573334 12.7844 0.573334C11.9934 0.573334 11.353 1.21372 11.353 2.00471C11.353 2.79571 11.9934 3.43609 12.7844 3.43609Z" fill="#0070C0"/>
                      </svg>
                      <span className="text-[#696969] text-center font-['Neue_Montreal'] text-sm md:text-base font-normal leading-[26px] uppercase">
                        MENORES:
                      </span>
                    </div>
                    <p className="text-black text-center font-['Neue_Montreal'] text-sm md:text-base font-medium leading-[26px] uppercase">
                      {formatPrice(option.children)}
                    </p>
                  </div>
                </div>
              </div>
            ))}

            {/* Quote Button */}
            <button
              onClick={handleQuoteClick}
              className="w-full py-3 px-6 rounded-full border-2 border-[#0070C0] bg-transparent text-[#0070C0] font-['Neue_Montreal'] text-base font-bold uppercase hover:bg-[#0070C0] hover:text-white transition-all"
            >
              COTIZA TU ENTRADA
            </button>

            {/* Disclaimer */}
            <p className="text-[#696969] text-center font-['Neue_Montreal'] text-xs leading-5">
              *se aplica precio regular, adultos (13 años en adelante), menores (3 a 12 años)
            </p>
          </>
        ) : (
          /* WhatsApp Button */
          <button
            onClick={handleWhatsAppClick}
            className="w-full py-4 px-6 rounded-full bg-[#0070C0] text-white font-['Neue_Montreal'] text-base font-bold uppercase hover:bg-[#0070C0]/90 transition-all flex items-center justify-center gap-3"
          >
            <MessageCircle size={24} fill="white" />
            COTIZA POR WHATSAPP
          </button>
        )}
      </div>

      {/* Quote Dialog */}
      <QuoteDialog 
        open={isQuoteDialogOpen} 
        onOpenChange={setIsQuoteDialogOpen}
        pricing={pricing}
      />
    </div>
  );
};
