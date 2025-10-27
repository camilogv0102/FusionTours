import React, { createContext, useState, useContext, ReactNode } from 'react';

type Currency = 'MXN' | 'USD';

type CurrencyContextType = {
  currency: Currency;
  setCurrency: (curr: Currency) => void;
  convertPrice: (priceMXN: number) => number;
  formatPrice: (priceMXN: number) => string;
};

const EXCHANGE_RATE = 0.055; // 1 MXN = 0.055 USD

const CurrencyContext = createContext<CurrencyContextType | undefined>(undefined);

export const CurrencyProvider = ({ children }: { children: ReactNode }) => {
  const [currency, setCurrency] = useState<Currency>('MXN');

  const convertPrice = (priceMXN: number) => {
    return currency === 'USD' ? priceMXN * EXCHANGE_RATE : priceMXN;
  };

  const formatPrice = (priceMXN: number) => {
    const convertedPrice = convertPrice(priceMXN);
    const symbol = '$';
    const currencyCode = currency;
    return `${symbol}${Math.round(convertedPrice).toLocaleString()} ${currencyCode}`;
  };

  return (
    <CurrencyContext.Provider value={{ currency, setCurrency, convertPrice, formatPrice }}>
      {children}
    </CurrencyContext.Provider>
  );
};

export const useCurrency = () => {
  const context = useContext(CurrencyContext);
  if (!context) {
    throw new Error('useCurrency must be used within CurrencyProvider');
  }
  return context;
};
