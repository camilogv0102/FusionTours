export interface PricingOption {
  location: string;
  adults: number;
  children: number;
  currency: string;
}

export interface Feature {
  icon: 'captain' | 'bus';
  text: string;
}

export interface Product {
  id: number;
  name: string;
  category: string;
  description: string;
  longDescription: string;
  images: string[];
  features: Feature[];
  includes: string[];
  pricing?: PricingOption[];
  requiresQuote: boolean;
  whatsappNumber?: string;
  whatsappMessage?: string;
  recommendations?: {
    title: string;
    enabled: boolean;
  };
  operationDays?: {
    title: string;
    content: string;
  };
  recommendedProducts?: number[];
}
