"""A labelled grid of one screenshot per theme, to look at many themes at once.

Usage: python3 scripts/contact-sheet.py <out.png> <shot> <theme> [<theme> ...]
  shot: 1-picker, 2-dashboard, 3-orders, 4-edit, 5-orders-dark or 6-back-to-aurora
Reads storage/lab/results/<theme>/<shot>.png (or LAB_OUT). Needs Pillow.
"""
import os
import sys

from PIL import Image, ImageDraw

out, shot, ids = sys.argv[1], sys.argv[2], sys.argv[3:]
root = os.environ.get('LAB_OUT', os.path.join(os.path.dirname(__file__), '..', 'storage', 'lab', 'results'))
cols, w, h = 4, 640, 400
rows = -(-len(ids) // cols)
sheet = Image.new('RGB', (cols * (w + 8), rows * (h + 30)), 'white')
draw = ImageDraw.Draw(sheet)
for i, theme in enumerate(ids):
    x, y = (i % cols) * (w + 8), (i // cols) * (h + 30)
    try:
        image = Image.open(os.path.join(root, theme, f'{shot}.png')).convert('RGB').resize((w, h))
    except OSError:
        image = Image.new('RGB', (w, h), 'grey')
    sheet.paste(image, (x, y + 26))
    draw.text((x + 4, y + 6), theme, fill='black')
sheet.save(out)
