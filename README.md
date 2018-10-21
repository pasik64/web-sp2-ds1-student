# DS1 - SP2 - student # 

Postup instalace:

1. Proveďte fork tohoto repozitáře
2. S využitím composeru nainstalujte závislosti:
    * <code>composer install</code> - přejděte do rootu projektu s spusťe příkaz composer install. 
    Automaticky bude vytvořena složka vendor a do této složky budou nainstalovány závislosti k projektu.
    Složka vendor se nikdy nepřidává do Vašeho repozitáře. Není to třeba. Proto je také přidána v .gitignore.
3. Přejděte do složky web/admin a s pomocí npm nainstalujte závislosti do složky node_modules:
    * <code>npm install</code>
    
    
Další informace:    
    * <code>composer dump-autoload</code> - aktualizace composeru podle composer.json souboru z rootu projektu,
     pokud byste někdy potřebovali aktualizaci.