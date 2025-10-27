import { Product } from '@/types/product';

export const products: Product[] = [
  {
    id: 1,
    name: 'TIBURÓN BALLENA',
    category: 'Mar',
    description: 'Nada con el pez más grande del océano',
    longDescription: 'Vive una experiencia única nadando junto al majestuoso tiburón ballena, el pez más grande del océano. Este tour te llevará a las aguas cristalinas del Caribe Mexicano donde podrás observar de cerca a estos gigantes gentiles en su hábitat natural. Acompañado de guías expertos certificados, disfrutarás de un día inolvidable lleno de aventura, naturaleza y aprendizaje sobre la conservación marina. La temporada de tiburón ballena es de mayo a septiembre, cuando estos increíbles animales visitan nuestras costas para alimentarse del plancton abundante.',
    images: [
      '/images/tours/whale-shark-1.jpg',
      '/images/tours/whale-shark-2.jpg',
      '/images/tours/whale-shark-1.jpg',
      '/images/tours/whale-shark-2.jpg'
    ],
    features: [
      { icon: 'captain', text: 'Tripulación certificada' },
      { icon: 'bus', text: 'Transporte ida y vuelta' }
    ],
    includes: [
      'Paseo en barco desde Cancún',
      'Transporte ida y vuelta desde tu hotel',
      'Tripulación certificada',
      'Snorkel con tiburón ballena',
      'Equipo de snorkel incluido',
      'Bebidas y snacks a bordo',
      'Box lunch',
      'Guía bilingüe certificado',
      'Chaleco salvavidas',
      'Toallas'
    ],
    pricing: [
      {
        location: 'DESDE PLAYA DEL CARMEN Y RIVIERA MAYA EN GENERAL',
        adults: 90,
        children: 70,
        currency: 'USD'
      },
      {
        location: 'DESDE CANCÚN',
        adults: 100,
        children: 80,
        currency: 'USD'
      }
    ],
    requiresQuote: false,
    recommendations: {
      title: 'RECOMENDACIONES Y RESTRICCIONES',
      enabled: true
    },
    operationDays: {
      title: 'DIAS DE OPERACION',
      content: 'Este tour opera de Mayo a Septiembre, sujeto a condiciones climáticas y disponibilidad de avistamiento.'
    }
  },
  {
    id: 2,
    name: 'CENOTES SAGRADOS',
    category: 'Tierra',
    description: 'Explora los cenotes místicos de la Riviera Maya',
    longDescription: 'Descubre la magia de los cenotes sagrados de la Riviera Maya en esta aventura única que te llevará a explorar tres cenotes diferentes, cada uno con su propia belleza y características únicas. Sumérgete en las aguas cristalinas de estos pozos naturales considerados sagrados por la civilización Maya. Nuestros guías expertos te acompañarán en esta experiencia inolvidable donde aprenderás sobre la historia, geología y significado cultural de estos maravillosos lugares. Disfruta nadando en aguas frescas y transparentes, admirando las formaciones rocosas milenarias y conectando con la naturaleza en su estado más puro.',
    images: [
      '/images/tours/cenote-snorkel.jpg',
      '/images/tours/cenote-snorkel.jpg',
      '/images/tours/cenote-snorkel.jpg',
      '/images/tours/cenote-snorkel.jpg'
    ],
    features: [
      { icon: 'captain', text: 'Guía certificado' },
      { icon: 'bus', text: 'Transporte ida y vuelta' }
    ],
    includes: [
      'Visita a 3 cenotes diferentes',
      'Transporte ida y vuelta desde tu hotel',
      'Guía certificado bilingüe',
      'Equipo de snorkel incluido',
      'Chaleco salvavidas',
      'Toallas',
      'Comida típica mexicana',
      'Bebidas refrescantes',
      'Acceso a vestidores y baños',
      'Seguro de viajero'
    ],
    requiresQuote: true,
    whatsappNumber: '5219841234567',
    whatsappMessage: 'Hola, me interesa el tour de Cenotes Sagrados. ¿Podrían darme más información sobre precios y disponibilidad?',
    recommendations: {
      title: 'RECOMENDACIONES Y RESTRICCIONES',
      enabled: true
    },
    operationDays: {
      title: 'DIAS DE OPERACION',
      content: 'Este tour opera todos los días de la semana, sujeto a condiciones climáticas favorables. Horarios: Salida 8:00 AM - Regreso 5:00 PM aproximadamente.'
    },
    recommendedProducts: [1]
  }
];

export const getProductById = (id: number): Product | undefined => {
  return products.find(product => product.id === id);
};
