import os
import emoji

def main():
    directory = r"c:\webapps\laravel\ArrendaOco_git\Arrendaoco\resources\views"
    for root, dirs, files in os.walk(directory):
        for file in files:
            if file.endswith(".php"):
                path = os.path.join(root, file)
                try:
                    with open(path, 'r', encoding='utf-8') as f:
                        lines = f.readlines()
                        for i, line in enumerate(lines):
                            emojis_found = [c for c in line if c in emoji.EMOJI_DATA]
                            if emojis_found:
                                print(f"{path}:{i+1}:{emojis_found}")
                except Exception as e:
                    pass

if __name__ == "__main__":
    main()
