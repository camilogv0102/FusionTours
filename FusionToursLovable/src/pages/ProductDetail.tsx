import { useParams, Link } from 'react-router-dom';
import { getProductById } from '@/data/products';
import { ProductGallery } from '@/components/ProductGallery';
import { FeatureBadge } from '@/components/FeatureBadge';
import { ProductSidebar } from '@/components/ProductSidebar';
import Header from '@/components/Header';
import Footer from '@/components/Footer';
import {
  Accordion,
  AccordionContent,
  AccordionItem,
  AccordionTrigger,
} from "@/components/ui/accordion";

const ProductDetail = () => {
  const { id } = useParams<{ id: string }>();
  const product = getProductById(Number(id));

  if (!product) {
    return (
      <div className="min-h-screen bg-white flex items-center justify-center">
        <p className="text-2xl font-['Neue_Montreal']">Producto no encontrado</p>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-white pt-[111px] max-md:pt-[85px] max-sm:pt-[75px]">
      <Header />
      
      {/* Gallery Section */}
      <section className="pt-4 md:pt-6 pb-16">
        <ProductGallery images={product.images} />
      </section>

      {/* Main Content + Sidebar */}
      <section className="max-w-[1341px] mx-auto px-5 pb-20">
        <div className="flex gap-[14px] flex-col md:flex-row">
          {/* Left Column - Main Content */}
          <div className="flex-1 flex flex-col gap-[56px]">
            {/* Feature Badges */}
            <div className="flex flex-col md:flex-row items-start md:items-center gap-4">
              {product.features.map((feature, index) => (
                <FeatureBadge key={index} icon={feature.icon} text={feature.text} />
              ))}
            </div>

            {/* Title */}
            <h1 className="text-[36px] md:text-[50px] font-['Neue_Montreal'] font-medium leading-[42px] md:leading-[54px]">
              {product.name}
            </h1>

            {/* Sidebar on Mobile - After Title */}
            <div className="md:hidden">
              <ProductSidebar 
                images={product.images}
                pricing={product.pricing}
                requiresQuote={product.requiresQuote}
                whatsappNumber={product.whatsappNumber}
                whatsappMessage={product.whatsappMessage}
              />
            </div>

            {/* Long Description */}
            <p className="text-[15px] md:text-base font-['Neue_Montreal'] font-normal leading-[24px] md:leading-[27px]">
              {product.longDescription}
            </p>

            {/* Blue Divider */}
            <div className="h-[2px] bg-[#0070C0] w-full" />

            {/* ¿QUÉ INCLUYE? Section */}
            <div className="flex flex-col gap-6">
              <h2 className="text-[36px] md:text-[50px] font-['Neue_Montreal'] font-medium leading-[42px] md:leading-[54px]">
                ¿QUE INCLUYE?
              </h2>
              <ul className="list-none space-y-2">
                {product.includes.map((item, index) => (
                  <li key={index} className="text-[15px] md:text-base font-['Neue_Montreal'] font-normal leading-[24px] md:leading-[27px]">
                    • {item}
                  </li>
                ))}
              </ul>
            </div>

            {/* Blue Divider */}
            <div className="h-[2px] bg-[#0070C0] w-full" />

            {/* Accordions */}
            <Accordion type="single" collapsible className="w-full">
              {product.recommendations?.enabled && (
                <AccordionItem value="recommendations" className="border-b-0">
                  <AccordionTrigger className="text-[#696969] font-['Neue_Montreal'] text-[16px] md:text-[20px] italic font-bold hover:no-underline text-left">
                    <div className="flex items-center gap-3">
                      <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fillRule="evenodd" clipRule="evenodd" d="M16.3251 20.075C15.9736 20.4261 15.497 20.6233 15.0001 20.6233C14.5032 20.6233 14.0267 20.4261 13.6751 20.075L6.60262 13.005C6.25104 12.6532 6.05359 12.1762 6.05371 11.6789C6.05383 11.1816 6.2515 10.7047 6.60324 10.3531C6.95499 10.0015 7.43199 9.80408 7.92931 9.8042C8.42664 9.80432 8.90354 10.002 9.25512 10.3537L15.0001 16.0987L20.7451 10.3537C21.0986 10.012 21.5721 9.8228 22.0638 9.82684C22.5554 9.83088 23.0257 10.0278 23.3736 10.3753C23.7214 10.7228 23.9188 11.193 23.9233 11.6846C23.9278 12.1762 23.739 12.6499 23.3976 13.0037L16.3264 20.0762L16.3251 20.075Z" fill="#696969"/>
                      </svg>
                      RECOMENDACIONES Y RESTRICCIONES
                    </div>
                  </AccordionTrigger>
                  <AccordionContent className="text-[15px] md:text-base font-['Neue_Montreal'] pt-4">
                    <ul className="space-y-2">
                      <li>• Llegar 15 minutos antes de la hora de salida</li>
                      <li>• Usar bloqueador solar biodegradable</li>
                      <li>• No tocar a los animales marinos</li>
                      <li>• Seguir las instrucciones del guía en todo momento</li>
                    </ul>
                  </AccordionContent>
                </AccordionItem>
              )}

              {product.operationDays && (
                <AccordionItem value="operation-days" className="border-b-0">
                  <AccordionTrigger className="text-[#696969] font-['Neue_Montreal'] text-[16px] md:text-[20px] italic font-bold hover:no-underline text-left">
                    <div className="flex items-center gap-3">
                      <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fillRule="evenodd" clipRule="evenodd" d="M16.3251 20.075C15.9736 20.4261 15.497 20.6233 15.0001 20.6233C14.5032 20.6233 14.0267 20.4261 13.6751 20.075L6.60262 13.005C6.25104 12.6532 6.05359 12.1762 6.05371 11.6789C6.05383 11.1816 6.2515 10.7047 6.60324 10.3531C6.95499 10.0015 7.43199 9.80408 7.92931 9.8042C8.42664 9.80432 8.90354 10.002 9.25512 10.3537L15.0001 16.0987L20.7451 10.3537C21.0986 10.012 21.5721 9.8228 22.0638 9.82684C22.5554 9.83088 23.0257 10.0278 23.3736 10.3753C23.7214 10.7228 23.9188 11.193 23.9233 11.6846C23.9278 12.1762 23.739 12.6499 23.3976 13.0037L16.3264 20.0762L16.3251 20.075Z" fill="#696969"/>
                      </svg>
                      {product.operationDays.title}
                    </div>
                  </AccordionTrigger>
                  <AccordionContent className="text-[15px] md:text-base font-['Neue_Montreal'] pt-4">
                    {product.operationDays.content}
                  </AccordionContent>
                </AccordionItem>
              )}
            </Accordion>

            {/* Blue Divider */}
            <div className="h-[2px] bg-[#0070C0] w-full" />

            {/* RECOMENDADOS Section - Dynamic */}
            {product.recommendedProducts && product.recommendedProducts.length > 0 && (
              <div className="flex flex-col gap-6">
                <h2 className="text-[36px] md:text-[50px] font-['Neue_Montreal'] font-medium leading-[42px] md:leading-[54px]">
                  RECOMENDADOS
                </h2>
                <div className="grid grid-cols-1 md:grid-cols-2 gap-[22px]">
                  {product.recommendedProducts.map((productId) => {
                    const recommendedProduct = getProductById(productId);
                    if (!recommendedProduct) return null;
                    
                    return (
                      <Link 
                        key={productId} 
                        to={`/product/${productId}`}
                        className="group"
                      >
                        <div 
                          className="flex flex-col justify-between h-[400px] md:h-[420px] p-[30px] md:p-[50px] rounded-[10px] bg-gradient-to-b from-transparent to-black/80 hover:scale-[1.02] transition-transform duration-300"
                          style={{
                            backgroundImage: `linear-gradient(180deg, rgba(0, 0, 0, 0.00) 0%, rgba(0, 0, 0, 0.80) 100%), url('${recommendedProduct.images[0]}')`,
                            backgroundSize: 'cover',
                            backgroundPosition: 'center'
                          }}
                        >
                          <span className="text-[#FFFDE5] font-['Neue_Montreal'] text-[17px] font-medium uppercase">
                            {recommendedProduct.category}
                          </span>
                          <div className="space-y-4">
                            <h3 className="text-[#FFFDE5] font-['Neue_Montreal'] text-[20px] md:text-[25px] font-medium leading-[28px] md:leading-[39px]">
                              {recommendedProduct.name}
                            </h3>
                            <p className="text-[#D2D2D2] font-['Neue_Montreal'] text-[15px] md:text-[17px] leading-[22px] md:leading-[27px]">
                              {recommendedProduct.description}
                            </p>
                          </div>
                        </div>
                      </Link>
                    );
                  })}
                </div>
              </div>
            )}
          </div>

          {/* Right Column - Sidebar (Desktop Only) */}
          <div className="hidden md:block">
            <ProductSidebar 
              images={product.images}
              pricing={product.pricing}
              requiresQuote={product.requiresQuote}
              whatsappNumber={product.whatsappNumber}
              whatsappMessage={product.whatsappMessage}
            />
          </div>
        </div>
      </section>

      <Footer />
    </div>
  );
};

export default ProductDetail;
