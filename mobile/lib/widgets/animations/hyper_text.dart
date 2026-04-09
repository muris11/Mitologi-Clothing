import 'dart:async';
import 'dart:math';
import 'package:flutter/material.dart';

class HyperText extends StatefulWidget {
  final String text;
  final TextStyle? style;
  final Duration duration;

  const HyperText({
    super.key,
    required this.text,
    this.style,
    this.duration = const Duration(milliseconds: 1000),
  });

  @override
  State<HyperText> createState() => _HyperTextState();
}

class _HyperTextState extends State<HyperText> {
  String _currentText = '';
  Timer? _timer;
  final _random = Random();
  final String _chars =
      'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz!@#\$%^&*()_+';
  int _ticks = 0;
  final int _maxTicks = 20;

  @override
  void initState() {
    super.initState();
    _startAnimation();
  }

  @override
  void didUpdateWidget(HyperText oldWidget) {
    if (oldWidget.text != widget.text) {
      _startAnimation();
    }
    super.didUpdateWidget(oldWidget);
  }

  void _startAnimation() {
    _timer?.cancel();
    _ticks = 0;
    _currentText = List.generate(widget.text.length, (_) => ' ').join();

    _timer = Timer.periodic(
      Duration(milliseconds: widget.duration.inMilliseconds ~/ _maxTicks),
      (timer) {
        _ticks++;

        if (_ticks >= _maxTicks) {
          setState(() {
            _currentText = widget.text;
          });
          timer.cancel();
        } else {
          final solvedCount = (widget.text.length * (_ticks / _maxTicks))
              .floor();

          final List<String> newChars = [];
          for (int i = 0; i < widget.text.length; i++) {
            if (i < solvedCount || widget.text[i] == ' ') {
              newChars.add(widget.text[i]);
            } else {
              newChars.add(_chars[_random.nextInt(_chars.length)]);
            }
          }

          setState(() {
            _currentText = newChars.join();
          });
        }
      },
    );
  }

  @override
  void dispose() {
    _timer?.cancel();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Text(_currentText, style: widget.style);
  }
}
