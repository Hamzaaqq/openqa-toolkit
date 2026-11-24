from html.parser import HTMLParser
from pathlib import Path
from urllib.parse import urlsplit, unquote
import xml.etree.ElementTree as ET

root = Path(__file__).resolve().parents[1] / "site"

class Page(HTMLParser):
    def __init__(self):
        super().__init__()
        self.ids = set()
        self.refs = []
        self.has_title = False
        self.has_description = False
        self.has_main = False
        self.has_canonical = False

    def handle_starttag(self, tag, attrs):
        a = dict(attrs)
        if "id" in a:
            assert a["id"] not in self.ids, a["id"]
            self.ids.add(a["id"])
        if tag == "title":
            self.has_title = True
        if tag == "main":
            self.has_main = True
        if tag == "meta" and a.get("name") == "description":
            self.has_description = bool(a.get("content"))
        if tag == "link" and a.get("rel") == "canonical":
            self.has_canonical = bool(a.get("href"))
        if tag == "img":
            assert "alt" in a, a
        for key in ("href", "src"):
            if key in a:
                self.refs.append(a[key])

pages = {}
for path in root.glob("*.html"):
    parser = Page()
    parser.feed(path.read_text(encoding="utf-8"))
    assert parser.has_title and parser.has_description and parser.has_main and parser.has_canonical, path
    pages[path.resolve()] = parser
assert len(pages) == 4
for path, page in pages.items():
    for ref in page.refs:
        parts = urlsplit(ref)
        if parts.scheme or parts.netloc:
            continue
        target = (path.parent / unquote(parts.path)).resolve() if parts.path else path
        if target.is_dir():
            target = target / "index.html"
        assert target.is_file(), (path, ref)
        if parts.fragment and target in pages:
            assert unquote(parts.fragment) in pages[target].ids, (path, ref)
ET.parse(root / "sitemap.xml")
print("Four pages checked: metadata, IDs, local links, image alt and sitemap.")
