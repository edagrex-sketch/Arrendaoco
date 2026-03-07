import os
import re

def main():
    directory = r"c:\webapps\laravel\ArrendaOco_git\Arrendaoco\resources\views"
    
    # A broad regex to catch emojis and symbols commonly used as emojis
    # Includes Miscellaneous Symbols, Dingbats, Emoticons, Miscellaneous Symbols and Pictographs, Transport and Map Symbols
    emoji_pattern = re.compile(
        "["
        "\U0001f600-\U0001f64f"  # emoticons
        "\U0001f300-\U0001f5ff"  # symbols & pictographs
        "\U0001f680-\U0001f6ff"  # transport & map symbols
        "\U0001f1e0-\U0001f1ff"  # flags (iOS)
        "\u2702-\u27b0"          # Dingbats
        "\u24c2-\u252f"
        "\u2600-\u26ff"          # Misc symbols
        "]+",
        re.UNICODE
    )

    for root, dirs, files in os.walk(directory):
        for file in files:
            if file.endswith(".php"):
                path = os.path.join(root, file)
                try:
                    with open(path, 'r', encoding='utf-8') as f:
                        lines = f.readlines()
                        for i, line in enumerate(lines):
                            matches = emoji_pattern.findall(line)
                            if matches:
                                print(f"{path}:{i+1}:{matches}")
                except Exception as e:
                    pass

if __name__ == "__main__":
    main()
