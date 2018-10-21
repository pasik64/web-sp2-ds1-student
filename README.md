# DS1 - SP2 - student # 

Postup instalace:

1. Proveďte fork tohoto repozitáře
2. S využitím composeru nainstalujte závislosti:
    * <code>composer install</code> - přejděte do rootu projektu s spusťe příkaz composer install. 
    Automaticky bude vytvořena složka vendor a do této složky budou nainstalovány závislosti k projektu.
    Složka vendor se nikdy nepřidává do Vašeho repozitáře. Není to třeba. Proto je také přidána v .gitignore.
3. Přejděte do složky web/admin a s pomocí npm nainstalujte závislosti do složky node_modules:
    * <code>npm install</code>
4. Ve složce ds1-core/docs najdete ERA model k aplikaci a rovnou skript ds1_uzivatele.sql pro vytvoření
tabulky uživatelů včetně prvního uživatele s loginem admin a heslem admin.    
5.  Ve složce ds1-local/config/ds1_web_student.cz_dev_config.inc.php najdete konfiguraci aplikace 
a údaje pro připojení k databázi. 
6. Přejděte na adresu localhost/Váš adresář/web/admin a měla by se Vám zobrazit administrace, kam 
se lze přihlásit s využitím zmíněného loginu a hesla.
    
Další informace:    
    * <code>composer dump-autoload</code> - aktualizace composeru podle composer.json souboru z rootu projektu,
     pokud byste někdy potřebovali aktualizaci.