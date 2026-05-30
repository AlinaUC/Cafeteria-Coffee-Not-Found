"""
Pruebas de Selenium para Coffee Not Found
Navegadores: Chrome, Edge, Firefox
"""

from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.chrome.service import Service as ChromeService
from selenium.webdriver.edge.service import Service as EdgeService
from selenium.webdriver.firefox.service import Service as FirefoxService
from selenium.common.exceptions import TimeoutException, NoSuchElementException
import time
import sys


class CoffeeNotFoundTester:
    """Clase para ejecutar pruebas en Coffee Not Found"""
    
    def __init__(self, navegador='chrome'):
        """
        Inicializar el driver del navegador
        :param navegador: 'chrome', 'edge', o 'firefox'
        """
        self.navegador = navegador
        self.driver = None
        self.url_base = 'http://127.0.0.1:8000'
        
    def iniciar_navegador(self):
        """Iniciar el navegador seleccionado"""
        print(f"\n{'='*60}")
        print(f"🌐 Iniciando {self.navegador.upper()}...")
        print(f"{'='*60}\n")
        
        try:
            if self.navegador.lower() == 'chrome':
                options = webdriver.ChromeOptions()
                options.add_argument('--start-maximized')
                options.add_argument('--disable-blink-features=AutomationControlled')
                self.driver = webdriver.Chrome(options=options)
                
            elif self.navegador.lower() == 'edge':
                options = webdriver.EdgeOptions()
                options.add_argument('--start-maximized')
                options.add_argument('--disable-blink-features=AutomationControlled')
                self.driver = webdriver.Edge(options=options)
                
            elif self.navegador.lower() == 'firefox':
                options = webdriver.FirefoxOptions()
                self.driver = webdriver.Firefox(options=options)
                self.driver.maximize_window()
                
            else:
                raise ValueError(f"Navegador '{self.navegador}' no soportado")
                
            print(f"✅ {self.navegador.upper()} iniciado correctamente\n")
            return True
            
        except Exception as e:
            print(f"❌ Error al iniciar {self.navegador}: {str(e)}")
            return False
    
    def cerrar_navegador(self):
        """Cerrar el navegador"""
        if self.driver:
            self.driver.quit()
            print(f"\n🔚 {self.navegador.upper()} cerrado\n")
    
    def esperar_elemento(self, by, valor, timeout=10):
        """Esperar hasta que un elemento esté presente"""
        try:
            elemento = WebDriverWait(self.driver, timeout).until(
                EC.presence_of_element_located((by, valor))
            )
            return elemento
        except TimeoutException:
            print(f"⏱️ Timeout esperando elemento: {valor}")
            return None
    
    def tomar_captura(self, nombre):
        """Tomar captura de pantalla"""
        try:
            nombre_archivo = f"captura_{self.navegador}_{nombre}_{int(time.time())}.png"
            self.driver.save_screenshot(nombre_archivo)
            print(f"📸 Captura guardada: {nombre_archivo}")
        except Exception as e:
            print(f"❌ Error al tomar captura: {str(e)}")
    
    # ===================== PRUEBAS =====================
    
    def test_01_pagina_principal(self):
        """Prueba 1: Verificar que la página principal carga correctamente"""
        print("🧪 TEST 1: Página Principal")
        print("-" * 40)
        
        try:
            self.driver.get(self.url_base)
            time.sleep(2)
            
            # Verificar título
            titulo = self.driver.title
            print(f"📄 Título: {titulo}")
            
            # Tomar captura
            self.tomar_captura("pagina_principal")
            
            if "Coffee" in titulo or "Not Found" in titulo or "Laravel" in titulo:
                print("✅ Página principal cargada correctamente\n")
                return True
            else:
                print("⚠️ Título no esperado\n")
                return False
                
        except Exception as e:
            print(f"❌ Error: {str(e)}\n")
            return False
    
    def test_02_registro(self):
        """Prueba 2: Verificar formulario de registro"""
        print("🧪 TEST 2: Formulario de Registro")
        print("-" * 40)
        
        try:
            self.driver.get(f"{self.url_base}/register")
            time.sleep(2)
            
            # Buscar campos del formulario
            campos = {
                'nombre': self.driver.find_elements(By.NAME, 'nombre'),
                'email': self.driver.find_elements(By.NAME, 'email'),
                'password': self.driver.find_elements(By.NAME, 'password'),
                'password_confirmation': self.driver.find_elements(By.NAME, 'password_confirmation')
            }
            
            campos_encontrados = sum(1 for campo in campos.values() if len(campo) > 0)
            print(f"📝 Campos encontrados: {campos_encontrados}/4")
            
            self.tomar_captura("formulario_registro")
            
            if campos_encontrados >= 3:
                print("✅ Formulario de registro encontrado\n")
                return True
            else:
                print("⚠️ No se encontraron todos los campos\n")
                return False
                
        except Exception as e:
            print(f"❌ Error: {str(e)}\n")
            return False
    
    def test_03_login(self):
        """Prueba 3: Verificar formulario de login"""
        print("🧪 TEST 3: Formulario de Login")
        print("-" * 40)
        
        try:
            self.driver.get(f"{self.url_base}/login")
            time.sleep(2)
            
            # Buscar campos del formulario
            email_field = self.driver.find_elements(By.NAME, 'email')
            password_field = self.driver.find_elements(By.NAME, 'password')
            
            print(f"📧 Campo email: {'Encontrado' if email_field else 'No encontrado'}")
            print(f"🔒 Campo password: {'Encontrado' if password_field else 'No encontrado'}")
            
            self.tomar_captura("formulario_login")
            
            if email_field and password_field:
                print("✅ Formulario de login encontrado\n")
                return True
            else:
                print("⚠️ Formulario incompleto\n")
                return False
                
        except Exception as e:
            print(f"❌ Error: {str(e)}\n")
            return False
    
    def test_04_login_completo(self):
        """Prueba 4: Intentar login con credenciales de prueba"""
        print("🧪 TEST 4: Login Completo")
        print("-" * 40)
        
        try:
            self.driver.get(f"{self.url_base}/login")
            time.sleep(2)
            
            # Ingresar credenciales (estudiante de prueba)
            email_field = self.driver.find_element(By.NAME, 'email')
            password_field = self.driver.find_element(By.NAME, 'password')
            
            email_field.clear()
            email_field.send_keys('estudiante@upds.edu.bo')
            
            password_field.clear()
            password_field.send_keys('password')
            
            print("📝 Credenciales ingresadas")
            
            # Buscar y hacer clic en el botón de login
            botones = self.driver.find_elements(By.TAG_NAME, 'button')
            for boton in botones:
                if 'Log in' in boton.text or 'Iniciar' in boton.text or 'Entrar' in boton.text:
                    boton.click()
                    print("🖱️ Botón de login clickeado")
                    break
            
            time.sleep(3)
            
            # Verificar si hay redirección al dashboard
            url_actual = self.driver.current_url
            print(f"🔗 URL actual: {url_actual}")
            
            self.tomar_captura("despues_login")
            
            if 'dashboard' in url_actual or 'menu' in url_actual:
                print("✅ Login exitoso - Redirigido correctamente\n")
                return True
            else:
                print("⚠️ Login no redirigió al dashboard\n")
                return False
                
        except Exception as e:
            print(f"❌ Error: {str(e)}\n")
            self.tomar_captura("error_login")
            return False
    
    def test_05_navegacion_menu(self):
        """Prueba 5: Verificar navegación al menú"""
        print("🧪 TEST 5: Navegación al Menú")
        print("-" * 40)
        
        try:
            # Primero hacer login
            self.driver.get(f"{self.url_base}/login")
            time.sleep(2)
            
            email_field = self.driver.find_element(By.NAME, 'email')
            password_field = self.driver.find_element(By.NAME, 'password')
            
            email_field.send_keys('estudiante@upds.edu.bo')
            password_field.send_keys('password')
            
            botones = self.driver.find_elements(By.TAG_NAME, 'button')
            for boton in botones:
                if 'Log in' in boton.text or 'Iniciar' in boton.text:
                    boton.click()
                    break
            
            time.sleep(3)
            
            # Navegar al menú
            self.driver.get(f"{self.url_base}/menu")
            time.sleep(3)
            
            print(f"🔗 URL actual: {self.driver.current_url}")
            
            # Buscar elementos del menú
            productos = self.driver.find_elements(By.CLASS_NAME, 'producto') or \
                       self.driver.find_elements(By.CLASS_NAME, 'card') or \
                       self.driver.find_elements(By.CLASS_NAME, 'item')
            
            print(f"🍽️ Productos encontrados: {len(productos)}")
            
            self.tomar_captura("pagina_menu")
            
            if 'menu' in self.driver.current_url:
                print("✅ Página de menú cargada\n")
                return True
            else:
                print("⚠️ No se pudo acceder al menú\n")
                return False
                
        except Exception as e:
            print(f"❌ Error: {str(e)}\n")
            return False
    
    def ejecutar_todas_pruebas(self):
        """Ejecutar todas las pruebas"""
        if not self.iniciar_navegador():
            return
        
        resultados = {
            'total': 0,
            'exitosas': 0,
            'fallidas': 0
        }
        
        pruebas = [
            ('Página Principal', self.test_01_pagina_principal),
            ('Formulario Registro', self.test_02_registro),
            ('Formulario Login', self.test_03_login),
            ('Login Completo', self.test_04_login_completo),
            ('Navegación Menú', self.test_05_navegacion_menu),
        ]
        
        for nombre, prueba in pruebas:
            resultados['total'] += 1
            try:
                if prueba():
                    resultados['exitosas'] += 1
                else:
                    resultados['fallidas'] += 1
            except Exception as e:
                print(f"❌ Error en {nombre}: {str(e)}\n")
                resultados['fallidas'] += 1
            
            time.sleep(1)
        
        # Reporte final
        print("\n" + "="*60)
        print(f"📊 REPORTE FINAL - {self.navegador.upper()}")
        print("="*60)
        print(f"Total de pruebas: {resultados['total']}")
        print(f"✅ Exitosas: {resultados['exitosas']}")
        print(f"❌ Fallidas: {resultados['fallidas']}")
        print(f"Porcentaje de éxito: {(resultados['exitosas']/resultados['total']*100):.1f}%")
        print("="*60 + "\n")
        
        self.cerrar_navegador()
        
        return resultados


def ejecutar_pruebas_multi_navegador():
    """Ejecutar pruebas en los 3 navegadores"""
    navegadores = ['chrome', 'edge', 'firefox']
    resultados_generales = {}
    
    print("\n" + "🚀"*30)
    print("PRUEBAS MULTI-NAVEGADOR - COFFEE NOT FOUND")
    print("🚀"*30 + "\n")
    
    for nav in navegadores:
        try:
            tester = CoffeeNotFoundTester(navegador=nav)
            resultados = tester.ejecutar_todas_pruebas()
            resultados_generales[nav] = resultados
            time.sleep(2)
        except Exception as e:
            print(f"❌ Error con {nav}: {str(e)}\n")
            resultados_generales[nav] = None
    
    # Reporte consolidado
    print("\n" + "="*60)
    print("📈 REPORTE CONSOLIDADO - TODOS LOS NAVEGADORES")
    print("="*60)
    
    for nav, resultado in resultados_generales.items():
        if resultado:
            exito = (resultado['exitosas']/resultado['total']*100)
            print(f"\n{nav.upper()}:")
            print(f"  ✅ Exitosas: {resultado['exitosas']}/{resultado['total']}")
            print(f"  📊 Éxito: {exito:.1f}%")
        else:
            print(f"\n{nav.upper()}: ❌ No se pudo ejecutar")
    
    print("\n" + "="*60 + "\n")


if __name__ == "__main__":
    # Puedes ejecutar un navegador específico o todos
    if len(sys.argv) > 1:
        navegador = sys.argv[1].lower()
        if navegador in ['chrome', 'edge', 'firefox']:
            tester = CoffeeNotFoundTester(navegador=navegador)
            tester.ejecutar_todas_pruebas()
        elif navegador == 'todos':
            ejecutar_pruebas_multi_navegador()
        else:
            print("Uso: python pruebas_selenium.py [chrome|edge|firefox|todos]")
    else:
        # Por defecto ejecutar todos
        ejecutar_pruebas_multi_navegador()