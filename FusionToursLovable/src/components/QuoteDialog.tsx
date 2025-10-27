import React, { useState } from 'react';
import { X, Calendar as CalendarIcon, ChevronLeft, ChevronRight } from 'lucide-react';
import { format } from 'date-fns';
import { es } from 'date-fns/locale';
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
} from "@/components/ui/dialog";
import { Calendar } from "@/components/ui/calendar";
import {
  Popover,
  PopoverContent,
  PopoverTrigger,
} from "@/components/ui/popover";
import { Button } from "@/components/ui/button";
import { cn } from "@/lib/utils";
import { useCurrency } from '@/contexts/CurrencyContext';
import { PricingOption } from '@/types/product';

interface QuoteDialogProps {
  open: boolean;
  onOpenChange: (open: boolean) => void;
  pricing?: PricingOption[];
}

export const QuoteDialog = ({ open, onOpenChange, pricing }: QuoteDialogProps) => {
  const [adults, setAdults] = useState(2);
  const [children, setChildren] = useState(0);
  const [date, setDate] = useState<Date>();
  const { formatPrice, currency } = useCurrency();

  // Get the first pricing option (can be extended to support multiple locations)
  const priceOption = pricing?.[0];
  
  // Calculate total
  const calculateTotal = () => {
    if (!priceOption) return 0;
    const adultsTotal = adults * priceOption.adults;
    const childrenTotal = children * priceOption.children;
    return adultsTotal + childrenTotal;
  };

  const total = calculateTotal();

  const handleAddToCart = () => {
    // TODO: Implement cart functionality
    console.log('Add to cart:', { adults, children, date, total });
  };

  const handlePay = () => {
    // TODO: Implement payment functionality
    console.log('Pay:', { adults, children, date, total });
  };

  const incrementAdults = () => setAdults(prev => Math.min(prev + 1, 20));
  const decrementAdults = () => setAdults(prev => Math.max(prev - 1, 1));
  const incrementChildren = () => setChildren(prev => Math.min(prev + 1, 20));
  const decrementChildren = () => setChildren(prev => Math.max(prev - 1, 0));

  return (
    <Dialog open={open} onOpenChange={onOpenChange}>
      <DialogContent className="sm:max-w-[600px] bg-white p-0 gap-0">
        {/* Close button */}
        <button
          onClick={() => onOpenChange(false)}
          className="absolute right-4 top-4 rounded-sm opacity-70 ring-offset-background transition-opacity hover:opacity-100 focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:pointer-events-none data-[state=open]:bg-accent data-[state=open]:text-muted-foreground z-50"
        >
          <X className="h-6 w-6" />
          <span className="sr-only">Close</span>
        </button>

        <DialogHeader className="px-6 pt-10 pb-6">
          <DialogTitle className="text-center text-4xl font-bold font-['Neue_Montreal']">
            COTICEMOS
          </DialogTitle>
        </DialogHeader>

        <div className="px-6 pb-8 space-y-8">
          {/* Adults and Children Selectors */}
          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            {/* Adults */}
            <div className="flex items-center justify-between gap-4 bg-[#E1EAF1] rounded-full px-6 py-4">
              <div className="flex items-center gap-3">
                <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <g clipPath="url(#clip0_adults_quote)">
                    <path d="M12.8928 10.2275C10.0048 10.1828 7.79864 7.86115 7.7791 5.11375C7.82681 2.25148 10.1663 0.0200715 12.8928 0C15.7834 0.109306 17.986 2.32186 18.0066 5.11375C17.9498 7.97828 15.6222 10.2081 12.8928 10.2275ZM17.0274 11.2883C20.6622 11.3308 22.5053 14.5192 22.5221 17.7076V25.5687H18.9588L18.9588 18.6598C18.872 18.1821 18.5485 18.0202 18.2521 18.067C17.9747 18.1109 17.721 18.3376 17.7075 18.6598V25.5687H7.80667V18.6598C7.73088 18.2052 7.44172 18.0304 7.1604 18.0481C6.85916 18.067 6.56693 18.3066 6.55542 18.6598V25.5687H3.04654V17.7076C3.02566 14.3096 5.17516 11.3135 8.56804 11.2883H17.0274Z" fill="#0070C0"/>
                  </g>
                  <defs>
                    <clipPath id="clip0_adults_quote">
                      <rect width="25.5687" height="25.5687" fill="white"/>
                    </clipPath>
                  </defs>
                </svg>
                <span className="text-[#2D2D2D] font-['Neue_Montreal'] text-sm font-medium uppercase">
                  ADULTOS
                </span>
              </div>
              <div className="flex items-center gap-3">
                <button
                  onClick={decrementAdults}
                  className="w-8 h-8 rounded-full bg-[#0070C0] flex items-center justify-center hover:bg-[#005a9f] transition-colors"
                >
                  <ChevronLeft className="w-5 h-5 text-white" />
                </button>
                <span className="text-[#2D2D2D] font-['Neue_Montreal'] text-xl font-bold min-w-[30px] text-center">
                  {adults}
                </span>
                <button
                  onClick={incrementAdults}
                  className="w-8 h-8 rounded-full bg-[#0070C0] flex items-center justify-center hover:bg-[#005a9f] transition-colors"
                >
                  <ChevronRight className="w-5 h-5 text-white" />
                </button>
              </div>
            </div>

            {/* Children */}
            <div className="flex items-center justify-between gap-4 bg-[#E1EAF1] rounded-full px-6 py-4">
              <div className="flex items-center gap-3">
                <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M12.7844 10.6568C14.0478 10.6568 15.0716 9.63302 15.0716 8.36966C15.0716 7.1063 14.0478 6.08252 12.7844 6.08252C11.521 6.08252 10.4973 7.1063 10.4973 8.36966C10.4973 9.63302 11.521 10.6568 12.7844 10.6568Z" fill="#0070C0"/>
                  <path d="M18.3587 13.5154C18.3587 11.4669 16.6958 9.804 14.6473 9.804H10.9216C8.87307 9.804 7.21021 11.4669 7.21021 13.5154V16.374H9.54878V25.5687H12.7844H16.0201V16.374H18.3587V13.5154Z" fill="#0070C0"/>
                  <path d="M12.7844 3.43609C13.5754 3.43609 14.2158 2.79571 14.2158 2.00471C14.2158 1.21372 13.5754 0.573334 12.7844 0.573334C11.9934 0.573334 11.353 1.21372 11.353 2.00471C11.353 2.79571 11.9934 3.43609 12.7844 3.43609Z" fill="#0070C0"/>
                </svg>
                <span className="text-[#2D2D2D] font-['Neue_Montreal'] text-sm font-medium uppercase">
                  MENORES
                </span>
              </div>
              <div className="flex items-center gap-3">
                <button
                  onClick={decrementChildren}
                  className="w-8 h-8 rounded-full bg-[#0070C0] flex items-center justify-center hover:bg-[#005a9f] transition-colors"
                  disabled={children === 0}
                >
                  <ChevronLeft className="w-5 h-5 text-white" />
                </button>
                <span className="text-[#2D2D2D] font-['Neue_Montreal'] text-xl font-bold min-w-[30px] text-center">
                  {children}
                </span>
                <button
                  onClick={incrementChildren}
                  className="w-8 h-8 rounded-full bg-[#0070C0] flex items-center justify-center hover:bg-[#005a9f] transition-colors"
                >
                  <ChevronRight className="w-5 h-5 text-white" />
                </button>
              </div>
            </div>
          </div>

          {/* Date Picker */}
          <div className="flex justify-center">
            <Popover>
              <PopoverTrigger asChild>
                <Button
                  variant="outline"
                  className={cn(
                    "bg-[#E1EAF1] border-0 rounded-full px-8 py-6 hover:bg-[#d5dfe8] transition-colors flex items-center gap-4 min-w-[300px]",
                    !date && "text-muted-foreground"
                  )}
                >
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM16.24 16.24L11 13V7H12.5V12.28L17 14.92L16.24 16.24Z" fill="#0070C0"/>
                  </svg>
                  <div className="flex items-center gap-3 flex-1">
                    <span className="text-[#2D2D2D] font-['Neue_Montreal'] text-sm font-medium uppercase">
                      FECHA
                    </span>
                    <span className="text-gray-400">▼</span>
                  </div>
                  <CalendarIcon className="h-6 w-6 text-[#0070C0]" />
                </Button>
              </PopoverTrigger>
              <PopoverContent className="w-auto p-0 bg-white" align="center">
                <Calendar
                  mode="single"
                  selected={date}
                  onSelect={setDate}
                  disabled={(date) => date < new Date()}
                  initialFocus
                  className={cn("p-3 pointer-events-auto")}
                  locale={es}
                />
              </PopoverContent>
            </Popover>
          </div>

          {/* Divider */}
          <div className="h-[1px] bg-[#0070C0]" />

          {/* Total */}
          <div className="text-center space-y-2">
            <div className="flex items-center justify-center gap-2">
              <span className="text-[#2D2D2D] font-['Neue_Montreal'] text-xl font-bold">
                TOTAL:
              </span>
              <span className="text-[#2D2D2D] font-['Neue_Montreal'] text-xl font-bold">
                {formatPrice(total)}
              </span>
            </div>
            <p className="text-[#6F6F6F] font-['Neue_Montreal'] text-xs">
              * No incluye el impuesto portuario ({currency === 'USD' ? '$20.00 USD' : '$400 MXN'}) adultos / ({currency === 'USD' ? '$5 USD' : '$100 MXN'} menores)
            </p>
          </div>

          {/* Action Buttons */}
          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            <button
              onClick={handleAddToCart}
              className="py-4 px-6 rounded-full bg-[#FF8B4C] text-white font-['Neue_Montreal'] text-base font-bold uppercase hover:bg-[#ff7a3a] transition-colors"
            >
              AGREGAR AL CARRITO
            </button>
            <button
              onClick={handlePay}
              className="py-4 px-6 rounded-full bg-[#FF8B4C] text-white font-['Neue_Montreal'] text-base font-bold uppercase hover:bg-[#ff7a3a] transition-colors"
            >
              PAGAR
            </button>
          </div>
        </div>
      </DialogContent>
    </Dialog>
  );
};
