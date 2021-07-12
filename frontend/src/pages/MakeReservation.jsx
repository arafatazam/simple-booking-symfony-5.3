import React, { useState } from "react";
import { Form, DatePicker, Button, Spin } from "antd";

export default function MakeReservation() {
  let [isSpinning, setSpinning] = useState(false);
  return (
    <Spin spinning={isSpinning}>
      <Form>
        <Form.Item>
          <DatePicker.RangePicker />
        </Form.Item>
        <Form.Item>
          <Button type="primary" htmlType="submit">
            Make Reservation
          </Button>
        </Form.Item>
      </Form>
    </Spin>
  );
}
